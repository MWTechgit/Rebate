@extends('emails.layout')

@section('title', 'Claim Denied')

@section('content')

{{ $firstName }}, thank you for your participation in
the Broward Water Partnership's Water Conservation and Incentives Program.
Unfortunately, we are unable to approve your rebate claim because:
<br />
<br />

{{ $reason }}
<br />
<br />

If you have any questions about this decision, you can contact us at {{ config('broward.contact.email') }}.

@endsection