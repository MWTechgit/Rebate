@extends('emails.layout')

@section('title', 'Claim Expired')

@section('content')
{{ $claim->applicant->full_name }}, thank you for applying for a toilet
rebate through the Broward Water Partnership's Water Conservation and Incentives
Program. Unfortunately, your rebate claim has expired.
<br /><br />
If you have any questions about this, you can contact us at {{ config('broward.contact.phone') }}.
@endsection