<?php
namespace App\Exports;

use App\Claim;
use App\ExportBatch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportClaimPropertyList implements FromCollection, WithHeadings, WithMapping {

	protected $batch;

	public function __construct(ExportBatch $batch) {
		$this->batch = $batch;
	}

	public function collection() {
		return $this->batch->claims;
	}

	public function headings(): array
	{
		return static::getTheHeadings();
	}

	public function map($claim): array
	{
		return static::getTheMapFor($claim);
	}

	public static function getTheMapFor(Claim $claim) {
		$address = optional(optional($claim->application)->property)->address;
		$applicant = $claim->applicant;

		return [
			strtoupper($claim->application->rebate_code),
			strtoupper($applicant->full_name ?: ''),
			strtoupper($address->line_one ?: ''),
			strtoupper($address->line_two ?: ''),
			strtoupper($address->city ?: ''),
			$address->postcode,
			$claim->approved_on,
		];
	}

	public static function getTheHeadings() {
		return [
			'RebateCode',
			'Name',
			'Address1',
			'Address2',
			'City',
			'Zip',
			'Approved',
		];
	}

}
