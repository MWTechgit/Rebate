<?php

namespace App\Http\Controllers\Applicant;

use App\Application;
use App\Applicant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function getLogin()
    {
        return view('applicant.login');
    }

    public function postLogin(Request $request)
    {
        $input = $request->validate([
            'email' => 'required|email',
            'application_number' => 'required|min:12',
        ]);

        $application = Application::where('rebate_code', 'LIKE', $input['application_number'].'%')->first();

        $invalid = redirect()->route('login')
            ->with('login_failed', 'Invalid Credentials')
            ->withInput()
        ;

        if (empty($application)) {
            return $invalid;
        }

        if ($application->applicant->email !== $request->input('email')) {
            return $invalid;
        }

        Auth::guard('applicant')->login($application->applicant);

        return redirect()->route('applications.status', ['application' => $application->id]);
    }

    public function logout()
    {
        Auth::guard('applicant')->logout();
        return redirect()->route('login');
    }
}
