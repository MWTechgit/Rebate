@extends('emails.layout')

@section('title', 'Claim Received')

@section('content')
{{ $claim->applicant->full_name }}, thank you for submitting your claim for a toilet
rebate through the Broward Water Partnership's Water Conservation and Incentives
Program. We have received it and it's currently being reviewed.
<br /><br />
If you have any questions about this, you can contact us at {{ $contact->phone }}.
@endsection