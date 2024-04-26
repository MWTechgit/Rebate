@extends('emails.layout')

@section('title', 'Claim Expiring')

@section('content')
Hello {{ $claim->applicant->first_name }}, 
<br><br>
Your rebate claim period will be expiring soon!

Please submit all of your documents as soon as possible so that we can 
review them and process your rebate. If you have not already installed 
your toilet, be sure to read your approval email carefully, as it includes 
all of the instructions on how to receive your rebate, including what 
toilet to purchase and what documents we need. 

<br><br>

Please send us the required documents online by 
<a href="{{ $rebate_center_url }}" title="Login to Rebate Center">clicking here</a>, 
or email the documents to <a href="{{ $contact->email }}">{{ $contact->email }}</a>.  
You may mail them to us at {{ $contact->address }}. However, please ensure that you 
mail your documents so that they arrive before the expiration date as explained in 
the <a href="{{ $terms_url }}" title="Program Terms and Conditions">Program Terms and Conditions</a>.

<br /><br />

If you have questions or need extra time, please email 
<a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a> or call us at 
<a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>.

<br /><br />

Thank you!
@endsection