@extends('emails.layout')

@section('title', 'Claim Approved')

@section('content')
Congratulations {{ $claim->applicant->first_name }}, your rebate claim has been approved.
<br /><br />
You will be receiving your check by mail in the coming weeks.  Thanks for your
participation and for conserving water!

@endsection