<?php

namespace App\Nova\Actions;

use App\Exports\ExportApplicants as Export;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Maatwebsite\Excel\Excel;

class ExportApplicants extends Action
{
    use SerializesModels;

    /**
     * The number of models that should be included in each chunk.
     *
     * @var int
     */
    public static $chunkCount = 50000;

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'Export Applicants';
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $date = now()->format('Y-m-d_H_i_s');
        $file = $date.'_applicants.xlsx';
        $disk = 'exports';

        $okay = (new Export($models) )->store($file,$disk,Excel::XLSX); // ('data.xlsx');

        if ($okay) {

            $url = Storage::disk($disk)->url($file);

            return Action::openInNewTab($url);

            // return Action::download( $url, $file);

        } else {

            return Action::danger('Whoops. Unable to build export.');

        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [

        ];
    }
}
