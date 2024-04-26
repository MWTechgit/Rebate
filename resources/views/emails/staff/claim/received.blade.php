@extends('emails.layout')

@section('title', 'Application Received')

@section('content')
Hi {{ $staff_name }},
<br />
<br />
A new rebate claim has been submitted {{ $claim->submission_type === 'online' ? 'online' : 'by ' . $claim->submission_type }}.

<h2>Claim Summary</h2>

<small>Applicant name:</small>
<br>
{{ $applicant_name }}
<br><br>

<small>Application Number:</small>
<br>
{{ $rebate_code }}
<br><br>

<small>Approved On:</small>
<br>
{{ $approved_on }}
<br><br>

<small>Rebate:</small>
<br>
{{ $rebate_name }}, {{ $partner_name }}
<br><br>

<hr>

What would you like to do?

<ul>
	<li><a href="{{ $claim_url }}">Review this rebate claim</a></li>
	<li><a href="{{ url('/admin') }}">Login to Rebate Center</a></li>
</ul>

@endsection