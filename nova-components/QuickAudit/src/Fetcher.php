<?php

namespace Bwp\QuickAudit;

use App\Address;
use App\Application;
use App\History;

class Fetcher {

	public function fetchItems(Application $application): array
	{
		$results = [
			'address' => $this->getMatchingAddresses($application),
			'utility' => $this->getMatchingUtilities($application),
			'name'    => $this->getMatchingNames($application),
			'email'   => $this->getMatchingEmails($application),
		];

		// \Log::debug('All matching results:', $results);

		return $results;
	}

	protected function getMatchingEmails(Application $application): array
	{

		if (!$application->applicant) {
			return ['error' => 'That applicant was not found.'];
			// throw new ShowUserError('That applicant was not found.');
		}

		$results = $this->emailQuery($application)->get();

		return $this->mapResults($results);
	}

	protected function getMatchingAddresses(Application $application): array
	{
		if (!optional($application->property)->address) {
			return ['error' => 'That address was not found.'];
		}

		$results = $this->addressQuery($application)->get();

		return $this->mapResults($results);
	}

	protected function getMatchingUtilities(Application $application): array
	{
		if (!optional($application->property)->utilityAccount) {
			return ['error' => 'That property or utility account was not found.'];
		}

		$results = $this->utilityQuery($application)->get();

		return $this->mapResults($results);
	}

	protected function getMatchingNames(Application $application): array
	{
		if (!$application->applicant) {
			return ['error' => 'That applicant was not found.'];
		}

		$results = $this->nameQuery($application)->get();

		return $this->mapResults($results);
	}

	protected function mapResults($results): array
	{
		return $results->map(function ($result) {
			$app = Application::withoutGlobalScopes()->find($result->application_id);
			$result->address = $result->line_one . ' ' . $result->line_two . ' ' . $result->city . ', ' . $result->state . ' ' . $result->postcode;
			$result->submitted = \Carbon\Carbon::parse($result->submitted_at)->format('F j, Y @ g:ia');
			$result->archived_application = is_null($app);
			$result->wait_listed = $app ? $app->wait_listed : 0;
			return $result;
		})->toArray();
	}

	public function fetchCount(Application $application): array
	{

		$errors = [];

		if ($application->applicant) {

			$emailCount = $this->emailQuery($application)->count();

			$nameCount = $this->nameQuery($application)->count();
		} else {
			$emailCount = 0;
			$nameCount = 0;
			$errors[] = 'The applicant record attached to this application was not found.';
		}

		if (optional($application->property)->address) {
			$addressCount = $this->addressQuery($application)->count();
		} else {
			$addressCount = 0;
			$errors[] = 'The property address record attached to this application was not found.';
		}

		if (optional($application->property)->utilityAccount) {
			$utilityCount = $this->utilityQuery($application)->count();
		} else {
			$utilityCount = 0;
			$errors[] = 'The utility account record attached to this application was not found.';
		}

		$items = [
			[
				'match' => 'name',
				'text' => "<b>$nameCount</b> application(s) matched the name <b>{$application->applicant->full_name}</b>",
				'count' => $nameCount,
				'passed' => $nameCount < 1,
			],
			[
				'match' => 'email',
				'text' => "<b>$emailCount</b> application(s) used a similar E-Mail address <b>{$application->applicant->email}</b>",
				'count' => $emailCount,
				'passed' => $emailCount < 1,
			],
			[
				'match' => 'address',
				'text' => "<b>$addressCount</b> application(s) used a similar property address",
				'count' => $addressCount,
				'passed' => $addressCount < 1,
			],
			[
				'match' => 'utility',
				'text' => "<b>$utilityCount</b> application(s) used a similar utility account number <b>" .
				(optional($application->property)->utilityAccount ? $application->property->utilityAccount->account_number : "UNKNOWN") .
				"</b>",
				'count' => $utilityCount,
				'passed' => $utilityCount < 1,
			],
		];

		return [
			'passed' => ($emailCount + $nameCount + $addressCount + $utilityCount) < 1,
			'items' => collect($items)->filter(function ($item) {
				return false === $item['passed'];
			})->toArray(),
			'errors' => $errors,
		];
	}

	private function utilityQuery(Application $application)
	{
		$utility = $application->property->utilityAccount;

		return \App\Nova\History::quickAudit($utility->account_number, $application->id)
			->orWhere(function ($query) use ($application, $utility) {

				$number = $utility->account_number;
				if (strlen($number) > 4) {
					$number = '%'.$number.'%';
				}
				$query->where('application_id', '!=', $application->id)
				->where('account_number', 'LIKE', $number);
		});
	}

	private function emailQuery(Application $application)
	{
		return \App\Nova\History::quickAudit($application->applicant->email, $application->id)
			->orWhere(function ($query) use ($application) {
				$query->where('application_id', '!=', $application->id)
					->where('email', 'LIKE', $application->applicant->email);
			});
	}

	private function nameQuery(Application $application)
	{
		return \App\Nova\History::quickAudit($application->applicant->full_name, $application->id)
			->orWhere( function ($query) use ($application) {
				$query->where('application_id', '!=', $application->id)
				->where('full_name', 'LIKE', $application->applicant->full_name);
			});
	}

	public function addressQuery(Application $application)
	{
		$address = $application->property->address;

		$query = History::where('application_id', '!=', $application->id);

		$query->where(function ($query) use ($address) {

			$query->where( function ($q) use ($address) {
				$index = Address::buildIndex($address);
				\App\Nova\History::applySearchToQuery($q, $index);
			});

			$query->orWhere(function ($query) use ($address) {
				\App\Nova\History::applySearchToQuery($query, $address->line_one);
				$this->whereSameCity($query, $address);
			});

			$query->orWhere( function ($query) use ($address) {
				$query->where('line_one', 'LIKE', $address->line_one);
				if (trim($address->line_two)) {
					$query->where('line_two', 'LIKE', trim($address->line_two));
				}
				$this->whereSameCity($query, $address);
			});

		});
		return $query;
	}

	private function whereSameCity($query, $address)
	{
		$zip = explode('-',$address->postcode)[0];

		$query->where('city', 'LIKE', $address->city);
		$this->whereSameState($query, $address->state);
		$query->where('postcode', 'LIKE', $zip);
	}

	private function whereSameState($query, $state)
	{
		if ( strtolower($state) === 'fl' || strtolower($state) === 'florida') {
			$query->whereIn('state', ['fl', 'florida', 'FL', 'FLORIDA', 'Florida']);
		} else {
			$query->where('state', 'LIKE', $state);
		}
	}
}