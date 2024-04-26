@extends('emails.layout')

@section('title', 'Reminder')

@section('content')
{{ $applicant->full_name }},
<br /><br />
This is a reminder from the Broward Water Partnership Toilet Rebate Program
Administrator. It has been 14 days
or more since we requested additional information regarding your application.
Please email the required information to {{ config('broward.contact.email') }},
or call us at {{ config('broward.contact.phone') }}.
If you call, please clearly state your first and last name before providing us
with the previously requested information.
<br /><br />
Unfortunately, according to
<a href="{{ $terms_url }}" title="Program Terms and Conditions">Program Terms and Conditions</a>,
if we do not receive the requested information within 14 days of the date of this notice,
then we must cancel your pending application. Once your application is canceled,
you must re-apply to be considered for a new rebate.
<br />
<br />
Thank you for your prompt attention to this request.
@endsection