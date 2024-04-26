@extends('emails.layout')

@section('title', 'Application Received')

@section('content')
Hi {{ $staff_name }},
<br />
<br />
A new rebate application has been received for {{ $partner_name }}.

<h2>Application Summary</h2>

<small>Applicant name:</small>
<br>
{{ $applicant_name }}
<br><br>

<small>Property address:</small>
<br>
{{ $address }}
<br><br>

<small>Rebate Item:</small>
<br>
{{ $rebate_name }}, {{ $rebates_remaining ? $rebates_remaining . ' left' : 'out of stock' }}
<br><br>

<small>Application Number:</small>
<br>
{{ $rebate_code }}
<br><br>

<hr>

<br><br>

For additional information, <a href="{{ $application_url }}">Log in to review this application</a>
@endsection