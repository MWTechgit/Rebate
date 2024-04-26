@extends('emails.layout')

@section('title', 'Application Approved')

@section('content')
Congratulations {{ $applicant->first_name }},
<br /><br />
You've been <strong>pre-approved</strong> for {{ $application->rebate_count }} WaterSense&copy; certified High-Efficiency Toilet (HET) 
{{ $application->rebate_count }} {{ \Illuminate\Support\Str::plural('rebate',$application->rebate_count) }} through the Broward Water Partnership's Water Conservation Program. 
<br/><br/>
Read through this carefully before purchasing your qualifying toilet. Please ensure that you purchase the correct toilet or you will not receive a rebate. 
Installing a WaterSense&copy;-certified HET is an important part of conserving water. Older toilets use up to 7 gallons per flush (gpf), so thank you for making 
the switch!
<br/><br/>
Application Number:  <strong>{{ $application->rebate_code }}</strong>
<br /><br />
The deadline to submit your claim is <strong>{{ $claim->expires_at }}</strong>. Be sure to submit the required documentation to the Rebate Administrator
by the deadline above. Failure to do so may result in your application being cancelled.
<br/><br/>
Here's what we need for you to get your rebate:

<ol>
    <li><strong>Itemized Invoice/Receipt</strong> with applicant's name, address, what was purchased, &amp; proof/method of payment<br />
        <strong>For each toilet</strong> your invoice/receipt must include:
        <ul>
            <li>Name and address of where the toilet was purchased</li>
            <li>Cost of the toilet</li>
            <li>Toilet make and model</li>
            <li>Parts and/or labor listed separately (if installed by plumber/contractor)</li>
            <li>Purchase date</li>
        </ul>
    </li>
    <li><strong>Proof that the new toilet uses 1.28 gpf or less.</strong> (If dual flush, be sure it uses 1.28 gpf or less for both flush options)
        If the gallons per flush is not specified, you must include a copy of the specifications which you may find inside the box or online.
        
        <br><br>
        <u>
        	<em>Please note:</em>
        	WaterSense&copy;-certified dual flush toilets <em>DO NOT</em> qualify for a rebate through this program if 
        	one of the flush options use more than 1.28 gallons per flush.</u>

        <br><br>

        Please ensure that you purchase the correct toilet or you will not receive a rebate.
    </li>
    <li><strong>Photo of toilet installed.</strong>
    	<br>
        Be sure the photo is clear and we can see the entire toilet, including the tank and some of the surroundings.
    </li>
</ol>


<h2>Tips and Details</h2>

    When submitting your receipts, the applicant's name must appear on the receipt 
	<strong>and must be the same name as the person who applied, and was pre-approved</strong> 
	for the rebate. If the applicant's name is not already printed on the sales receipt, please handle as follows:

<ul>
    <li>For applicants planning to purchase toilets at the <strong>Home Depot</strong> or <strong>Lowe's</strong>, 
        the toilets <strong>must</strong> be purchased at the Special Services or Customer Service desk
        (not in the standard check-out line) and obtain an invoice, which should include
        the applicant's name and address. This must either be validated
        and show proof of payment or accompanied by a register receipt.
    </li>
    <li>For toilets purchased at other stores or through contractors/vendors, 
        request an invoice showing proof of payment or a handwritten receipt
        from the store/vendor on their letterhead with a signature and
        printed name of an employee of the store/vendor.
    </li>
</ul>

<strong>What costs may be included other than the toilet in the Total Rebate Amount?</strong> (Receipts must be itemized)

<ul>
    <li>Wax Ring</li>
    <li>Supply line</li>
    <li>Bolts</li>
    <li>New shut-off valve, if needed</li>
</ul>

<em>** Please note that the cost of the toilet seat, if purchased separately, and any sales taxes, are not eligible.</em>

<br /><br />

The maximum amount of each rebate is {{ $rebate_value }}, or the cost of the toilet and accessories listed above, whichever is less. 

<br><br>
    
<strong>Tip:</strong> If your new toilet has a flapper, please buy a spare one &amp; ensure
    it is specifically made for your toilet. Flappers wear out over time
    and may leak without a sound, resulting in wasted water and money,
    defeating the purpose of your new HET! (Please note that the cost of
    a spare flapper is not included in the rebate)
    
<br /><br />

<strong>Don't forget</strong> to properly dispose of your old toilet because it may not be reused.

<h2>Submitting Your Receipt and Documentation</h2>

Please be sure to include your Application Number (provided above) and name when submitting your documentation. Here are ways you can submit your documents:
<ol>
    <li><strong>Online.</strong> Use your email address and your Application Number to access our
        online Rebate Center. There, you can monitor the status of your rebate claim, print a copy of your application and upload your
        documents. Rebate Center: <a href="{{ $rebate_center_url }}">{{ $rebate_center_url }}</a>
    </li>
    <li><strong>U.S. Postal Mail.</strong> If sending your documentation through the U.S. mail, please send to
        Attn: Rebate Administrator at <strong>{{ $contact->address }}</strong>
    </li>
    <li><strong>Email.</strong> Send your documents with your name and application number to
         <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>.
    </li>
</ol>

<br><br>

Thank you again for choosing to take an active role in conserving water. If you have any questions, 
please email the Rebate Administrator at 
<a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a> or call {{ $contact->phone }}.
</p>

@endsection