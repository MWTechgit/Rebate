<?php

namespace App\Jobs;

use App\Application;
use App\Claim;
use App\Nova\Application as NovaResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;

final class TracksNextFromLens
{

    protected static $debugging = false;


    static public function saveLensData(Builder $query, $lensClass, $column = 'id') 
    {
        if ( static::$debugging ) {
            Log::debug('Saving data from ' . class_basename($lensClass));
        }

        $ids = $query->limit(50)->pluck($column)->toArray();
        $session_key = class_basename($lensClass) . '__ids';
        session([$session_key => $ids]);
    }

    static public function saveIndexData(Builder $query, $modelClass)
    {

        if ( static::$debugging ) {
            Log::debug('Saving data from ' . class_basename($modelClass));
        }

        $appClass = '\App\\'.class_basename($modelClass);
        $column = (new $appClass)->getQualifiedKeyName();

        $ids = $query->limit(50)->pluck($column)->toArray();
        $session_key = class_basename($modelClass) . '__ids';
        session([$session_key => $ids]);        
    }

    static public function getNextId(Request $request, $model, $lens)
    {

        if ( $lens ) {
            $data = static::getLensData($request, $lens, $model) ?? [];
        } else {
            $data = static::getModelData($request, $model) ?? [];
        }

        $index = array_search($model->id, $data);
        if ( $index !== false ) {
            return Arr::get($data,$index + 1);
        }

        if ( static::$debugging ) {
            Log::debug('Model ' . $model->id . ' not found in array:', ['d' => $data ]);
        }
    }

    static private function getModelData(Request $request, $model) 
    {
        $class = get_class($model);
        $session_key = class_basename($class) . '__ids';
        $data = $request->session()->get($session_key);
        if ( static::$debugging && empty($data)) {
            Log::debug('Empty data from ' . $session_key, ['s' => $request->session()->all() ]);
        }
        return $data;
    }


    static private function getLensData(Request $request, $lens, $model) 
    {
        $class = static::getLensClassName($request, $lens, $model);
        $session_key = class_basename($class) . '__ids';
        $data = $request->session()->get($session_key);
        if ( static::$debugging && empty($data)) {
            Log::debug('Empty data from ' . $session_key, ['s' => $request->session()->all() ]);
        }

        // Log::debug('Checking ' . $session_key );

        return $data;
    }

    static private function getLensClassName(Request $request, $lensUriKey, $model)
    {

        if ( $model instanceof Claim && $lensUriKey === 'approved') {
            return 'ApprovedClaims';
        }

        if ( $model instanceof Claim && $lensUriKey === 'denied') {
            return 'DeniedClaims';
        }

        if ( $lensUriKey === 'new-claims') {
            return 'NewClaims';
        }

        if ( $lensUriKey === 'application-inbox') {
            return 'ApplicationInbox';
        }

        if ( $lensUriKey === 'approved') {
            return 'ApprovedApplications';
        }

        if ( $lensUriKey === 'denied') {
            return 'DeniedApplications';
        }

        if ( $lensUriKey === 'special-attention') {
            return 'SpecialAttention';
        }

        if ( $lensUriKey === 'water-savers') {
            return 'WaterSavers';
        }

        if ( $lensUriKey === 'expiring-soon') {
            return 'ExpiringSoon';
        }

        if ( $lensUriKey === 'unclaimed') {
            return 'Unclaimed';
        }

        if ( $lensUriKey === 'pending-export') {
            return 'PendingExport';
        }

        if ( $lensUriKey === 'expired-recently') {
            return 'ExpiredRecently';
        }


        // fallback
        $lenses = static::allLenses($request);
        $class = $lenses->first( function ($lens) use ($lensUriKey) {
            return $lens->uriKey() === $lensUriKey;
        });
        if ( !$class ) {
            if ( static::$debugging ) {
                Log::debug('Cant find lens for ', ['l' => $lensUriKey]);
            }
            abort(404);
        }
        return get_class($class);
    }


    static private function allLenses(Request $request) {

        // $request = app()->make(NovaRequest::class);

        $resource = new NovaResource([]);

        return collect( $resource->lenses($request) );
    }

    // static private function theOldWay() {
        
    //     $query = Application::query();

    //     if ( $lens ) {

    //         $fake = app()->make(LensRequest::class);

    //         $query = static::getLens($request, $lens)->query($fake, $query);

    //     }

    //     Log::debug('Trying to get next after ' . $previous->id, [
    //         'lens' => $lens,
    //         'session' => $request->session()->all() 
    //     ]);

    //     // Now get the one after the given $previous
    //     static::queryAfter($query, $fake, $previous, $lens);

    //     // Pluck the ID
    //     return $query->take(1)->value('id');
    // }

/**
     * Get the lens
     * @param  [type] $uriKey [description]
     * @return [type]         [description]
     */
    protected function getLens(Request $request, $uriKey)
    {

        $fake = app()->make(NovaRequest::class);

        $resource = new NovaResource([]);

        $lens = collect( $resource->lenses($request) )
            ->first( function ($lens) use ($uriKey) {
            return $lens->uriKey() === $uriKey;
        });

        return $lens ?: abort(404);
    }

    /**
     * Take the next claim based on the lens orderBy 
     * @param  Builder $query 
     * @param  Application   $previous 
     * @param  string  $lens  
     * @return Builder
     */
    // protected function queryAfter(Builder $query, LensRequest $request, $previous, $lens)
    // {

    //     if ( is_null($lens) ) {
    //         $query->whereIn('status', [Claim::ST_NEW, Claim::ST_PENDING_REVIEW])->orderByDesc('submitted_at');
    //         if ( $previous->submitted_at ) {
    //             $query->where('submitted_at', '<', $previous->submitted_at);
    //         }
    //         return $query;              
    //     }

    //     switch ($lens){
    //         case 'application-inbox':
    //             return $query
    //                 ->whereIn('status', [Application::ST_NEW, Application::ST_PENDING_REVIEW])
    //                 ->where('created_at', '>', $previous->created_at);// );

    //         case 'approved':
    //             return $query->where('application_transactions.created_at', '<', optional($previous->transaction)->created_at);

    //         case 'denied':
    //             return $query->where('application_transactions.created_at', '<', optional($previous->transaction)->created_at);

    //         case 'special-attention':
    //             return $query->where('created_at', '>', $previous->created_at);

    //         case 'water-savers':
    //             return $query->where('created_at', '<', $previous->created_at);

    //         case 'expiring-soon':
    //             return $query->where('expires_at', '>', $previous->expires_at);
    //         case 'expired-recently':
    //             return $query->where('expired_at', '<', $previous->expired_at);
    //         case 'unclaimed':
    //             return $query->where('application_transactions.created_at', '<', optional($previous->application->transaction)->created_at);
    //         case 'pending-export':
    //             return $query->where('claim_transactions.created_at', '<', optional($previous->transaction)->created_at);
    //         case 'denied':
    //             return $query->where('claim_transactions.created_at', '<', optional($previous->transaction)->created_at);
    //         case 'approved':
    //             return $query->where('claim_transactions.created_at', '<', optional($previous->transaction)->created_at);
    //         default:
    //             return $query->where('created_at', '<', $previous->created_at);
    //     }

    //     }

    // }

}
