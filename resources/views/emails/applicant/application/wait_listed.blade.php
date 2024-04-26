@extends('emails.layout')

@section('title', 'Thank you')

@section('content')
{{ $applicant->full_name }},  thank you for completing the rebate
application. Unfortunately, there are no rebates available at the moment, but we
have added you to our waiting list and will notify you when one
becomes available in your area.
<br /><br />
Below you will find your confirmation code. Please save it for your records. If you need to
contact us about your status on the waiting list, please provide us
with your confirmation code so that we can easily retrieve your records.
<br /><br />
<b>Confirmation code: {{ $application->rebate_code }}</b><br />
<b>Email address: {{ $applicant->email }}</b>
<br /><br />
<em>
** Do not purchase your toilet unless you have received your Approval Confirmation
Notification and Number. Toilets purchased before you have been
approved may not be eligible for a rebate.**
</em>
@endsection