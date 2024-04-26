<?php
namespace App\Exports;

use App\Address;
use App\Applicant;
use App\Application;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportApplicants implements FromCollection, WithHeadings, WithMapping {

	use Exportable;

	private $models;

	public function __construct($models) {
		$models->load('applicant', 'property.address', 'claim');
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
		$address     = ( $property ? optional($property->address) : null ) ?? new Address;

		if ( !$applicant->claim ) {
			$status = $application->status;
		} else{
			$status = 'claim ' . $applicant->claim->status;
		}
		return [
			strtoupper( $application->rebate_code ?: ''),
			strtoupper( $applicant->full_name ?: ''),
			$applicant->email,
			strtoupper( $address->line_one ?: ''),
			strtoupper( $address->line_two ?: ''),
			strtoupper( $address->city ?: ''),
			$address->postcode,
			$status,
			$application->created_at,
		];
	}

	public static function getTheHeadings()
	{
		return [
			'RebateCode',
			'Name',
			'Email',
			'Address1',
			'Address2',
			'City',
			'Zip',
			'Status',
			'Submitted'
		];
	}

}
