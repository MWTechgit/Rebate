<?php

namespace App\Http\Middleware;

use Closure;

class StoreSessionLens
{
    public function handle($request, Closure $next)
    {
        if ( $request->isMethod('get') && $request->is('nova-api/applications/lens/*') ) {
        	$request->session()->put('last-application-lens', $request->lens);
        } elseif ( $request->isMethod('get') && ( $request->is('nova-api/claims/lens/*') || $request->is('nova-api/claims'))) {
        	$request->session()->put('last-claim-lens', $request->lens);
        }

        $response = $next($request);

        // This will set the data on the Panel component but it will not be passed to the ApplicationActions component
        // 
        // $lens = $request->session()->get('last-lens');
        // if ( $lens 
        // 	&& $request->isMethod('get') 
        // 	&& $response->status() === 200 
        // 	&& $this->isActionsComponent($request) 
        // ) {
        // 	$response->setData($this->appendLensName($response->getData(), $lens));
        // }

        return $response;
    }

    protected function isActionsComponent($request)
    {
    	return preg_match('~nova-api/(applications|claims)/[1-9]\d*$~i', $request->decodedPath()) === 1;
    }

    protected function appendLensName($data, $lens)
    {
    	$data->panels = collect($data->panels)
	    	->map( function ($panel) use ($lens) {
	    		if ( in_array($panel->name, ['Application Actions', 'Claim Actions']) ) {
	    			$panel->cameFromLens = $lens;
		    	}
		    	return $panel;
		    })->all();
		return $data;
    }
}
