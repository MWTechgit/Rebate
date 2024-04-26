<?php
namespace App\Exports;

use App\ExportBatch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportBatchExport implements FromCollection, WithHeadings, WithMapping {

	protected $exportBatch;

	public function __construct(ExportBatch $exportBatch) {
		$this->exportBatch = $exportBatch;
	}

	public function collection() {
		return $this->exportBatch->claims;
	}

	public function headings(): array
	{
		return static::getTheHeadings();
	}

	public function map($claim): array
	{
		return static::getTheMapFor($claim);
	}

	public static function getTheMapFor($claim) {
		$address = optional($claim->application->getMailToAddress());
		$applicant = $claim->applicant;

		return [
			'EVR' . $claim->id,
			strtoupper($claim->application->rebate_code),
			strtoupper($applicant->full_name ?: ''),
			strtoupper($address->line_one ?: ''),
			strtoupper($address->line_two ?: ''),
			strtoupper($address->city ?: ''),
			strtoupper($address->state ?: ''),
			$address->postcode,
			$address->country,
			$claim->amount_awarded,
			$claim->approved_on,
		];
	}

	public static function getTheHeadings() {
		return [
			'ClaimID',
			'RebateCode',
			'PayeeName',
			'PayeeAddress1',
			'PayeeAddress2',
			'PayeeCity',
			'PayeeState',
			'PayeeZip',
			'PayeeCountry',
			'Amount',
			'Approved',
		];
	}

}
