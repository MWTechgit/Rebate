@extends('emails.layout')

@section('title', 'Thank you')

@section('content')
{{ $applicant->full_name }},  thank you for completing the rebate application. We have received your request and it's pending review.
Once your application has been reviewed we will notify you whether it's been approved or denied.
<br />
<br />
Please allow up to five (5) business days to receive a email confirmation of "Approval or Denial" from the Rebate Administrator regarding your application.
If approved, you will receive an email with all of the instructions on how to receive your rebate. <span style="text-decoration:underline;">Please read that email carefully</span>.
<br />
<br />
Below you will find your Application Number. Please save it for your records. If you need to contact us about your rebate application,
please provide us with your Application Number so that we can easily retrieve your records.
You will also be able to use your Application Number to access our online Rebate Center to check on the status of your application.
<br />
<br />
<b>Application Number: {{ $application->rebate_code }}</b><br /><b>Email address: {{ $applicant->email }}</b>
<br /><br />
<em>
** Do not purchase your toilet unless you have received your Approval Confirmation Notification and Number. Toilets purchased before you have been approved may not be eligible for a rebate.**
</em>
<br /><br />
<span style="color:#7fa500">While you’re waiting for your approval, take advantage of free water-saving showerheads and faucet aerators. <a style="color:#0995d0" href="{{ config('broward.partner_url') }}">See if your community is participating</a>!</span>
@endsection