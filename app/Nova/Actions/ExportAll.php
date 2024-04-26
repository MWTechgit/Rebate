<?php

namespace App\Nova\Actions;

use App\Exports\ExportAll as Export;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Maatwebsite\Excel\Excel;

class ExportAll extends Action
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
        return 'Export Full Data';
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
        $file = $date.'_data.xlsx';
        $disk = 'exports';

        $okay = (new Export($models) )->store($file,$disk,Excel::XLSX); // ('data.xlsx');

        if ($okay) {

            // Storage::disk($disk)->setVisibility($file,'public');

            // $path = Storage::disk($disk)->path($file);

            // chmod($path, 0777);  //changed to add the zero

            $url = Storage::disk($disk)->url($file);

            return Action::openInNewTab($url);

            // return Action::download( $url, $file);

            // return Action::message('It worked!');

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
