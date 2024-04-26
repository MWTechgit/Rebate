<?php
namespace App\Exports;

use App\Address;
use App\Applicant;
use App\Application;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportAll implements FromCollection, WithHeadings, WithMapping {

	use Exportable;

	private $models;

	public function __construct($models) {

		$models->load(
			'applicant',
			'address',
			'property.address',
			'claim.transaction',
			'property.utilityAccount',
			'transaction'
		);
		$this->models = $models;
	}

	public function collection() {
		return $this->models;
	}

	public function headings(): array
	{
		return static::getTheHeadings();
	}

	public function map($applicant): array
	{
		return static::getTheMapFor($applicant);
	}

	public static function getTheMapFor(Application $application)
	{
		$applicant   = optional($application->applicant);
		$property    = optional($application)->property;
		$address     = optional($property->address);
		$utility     = optional($property->utilityAccount);
		$claim       = optional($application->claim);
		$mailing     = optional($application->address ?: $property->address);

		return [
			$utility->account_number,
			$applicant->first_name,
			$applicant->last_name,
			$applicant->email,
			$address->line_one,
			$address->line_two,
			$address->city,
			$address->state,
			$address->postcode,
			$property->building_type,
			$property->subdivision_or_development,
			$application->status,
			optional($application->transaction)->description . ' ' . optional($application->transaction)->extended_description,
			$application->created_at,
			$property->toilets,
			$application->rebate_count,
			$claim->status,
			optional($claim->transaction)->description,
			$claim->amount_awarded,
			$claim->expired_at ?: optional($claim->transaction)->updated_at,
			$mailing->line_one,
			$mailing->line_two,
			$mailing->city,
			$mailing->state,
			$mailing->country,
			$mailing->postcode
		];
	}

	public static function getTheHeadings()
	{
		return [
			'Utility',
			'First Name',
			'Last Name',
			'Email Address',
			'Property Address',
			'Apartment Number',
			'City',
			'State',
			'Zip',
			'Building Type',
			'Subdivision or Development',
			'Application Approved/Denied',
			'Reason for Application Denial',
			'Date of Application',
			'Total Number of Toilets',
			'Number of Rebates',
			'Claim Approved/Denied/Expired',
			'Claim Denial Reason',
			'Amount Awarded',
			'Date of Claim Approval/Denial/Expiration',
			'Mailing Address',
			'Mailing Address Apartment Number',
			'Mailing Address City',
			'Mailing Address State',
			'Mailing Address',
			'Mailing Address Zip Code'
		];
	}

}
