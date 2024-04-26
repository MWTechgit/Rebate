@extends('emails.layout')

@section('title', 'Application Denied')

@section('content')
{{ $name }},  thank you for applying for a toilet rebate through the Broward Water Partnership's Water Conservation and Incentives
Program. Unfortunately, we are unable to approve your application because:
<br />
<br />

{{ $reason }}
<br />
<br />

If you have any questions about this decision, you can contact us at {{ config('broward.contact.email') }}.
@endsection