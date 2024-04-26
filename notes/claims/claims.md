# Overview

What is the relation between claim and rebate?

There aren't that many rebates per year.
Rebates have many claims, claims belong to rebate.

Applicants can have only ONE application
and only ONE claim.

People can't sign in and make multiple claims that
belong to them. They basically make a new account
for every Claim they want to make. That is why
we have "applicants" and not "users".

Every person that submits an application for a rebate
must have their application approved before making a Claim.

If the application is approved, the applicant then has approval to go
and buy the item they are seeking the rebate for and submit their documents
related to the purchase. This submission of documents represents a "claim" on a rebate.
Claims must be reviewed and approved just like an application does.

If the Claim is approved, the rebate->quantity is reduced
by the rebate_count of the claims Application (applications.rebate_count)
(the claim can have multiple toilets)

# Statuses
* [new](#new)
* [approved](#approved)
* [denied](#denied)
* [not-claimed](#not-claimed)
* [pending-review](#pending-review)
* [pending-fulfillment/pending-export](#pending-fulfillment)
* [fulfilled](#fulfilled)
* [expired](#expired)
* [expiring-soon](#expiring-soon)
* [expired-recently](#expired-recently)

------------------------------

## Flow

#### Old flow
```
new => pending-review => approved
approved => pending-fulfillment => fulfilled
new => pending-review => denied
```
#### New flow
New flow skips "approved" because it is never really used in the old system.
As soon as they approve a claim they mark it as "approved" then immediately mark it as "pending-fulfillment". Basically a claim that is pending-fulfillment is an approved claim. Let's just forget about "approved" status.
```
new => pending-review => pending-fulfillment
pending-fulfillment => fulfilled
new => pending-review => denied
```

------------------------------

## Not Claimed

This is the default status.

A better name for this would be "in-progress". Any claim that an applicant starts is "not-claimed" until it is submitted (all docs uploaded and claim submitted).

These are claims for applications that have an approved application but whose owners have not yet mailed or uploaded the necessary documents.

These claims SHOULD NOT show up in the admin unless the admin clicks on "Unclaimed" claims index.

When an expired claim is restored, the status should be set to 'not-claimed'.

```php
if ($claim->status == 'not-claimed')
    Not yet submitted
endif
```

------------------------------

## New

A claim is created as soon as an applicant STARTS their claim.
They might start a claim and finish it later, or never finish it.
This is why the default status of a claim is 'not-claimed'.

Once an applicant completes final submission of their claim,
it is considered "new". It is "new" until an applicant clicks on it
in the admin and then clicks "review". Which changes the status to
'pending-review'.

------------------------------

## Pending Review

* The claim.application was approved
* The claim has been submitted by applicant
* The admin has clicked "review" on the claim (examined the claim)

------------------------------

## Denied

**Any denied claim should have a `Transaction` with type "denied".**

We set the claim.status = 'denied' when the we create the transaction.
This allows us to do simple filter queries w/o joining tables.

A Claim is denied when an Admin denies the Claim.

------------------------------

## Approved

**Any approved claim should have a `Transaction` with type "approved".**

Approved is more of a "virtual" status. We never set status to "approved".

We use "pending-fulfillment" to approve a claim, we just skip "approved" as it isn't really used or needed.

A Claim is considered approved if it has any of the following statuses:
* ~~approved~~
* pending-fulfillment
* fulfilled

```php
public static function get_approved_query() {
    $rebate_claims = new Rebate_claim();
    $rebate_claims->where('status', 'approved');
    $rebate_claims->or_where('status', 'pending-fulfillment');
    $rebate_claims->or_where('status', 'fulfilled');
    $rebate_claims->order_by('approved_at', 'desc');
    return $rebate_claims;
}
```

------------------------------

## Pending Fulfillment/Pending Export

The claim has been approved. When a Claim is approved, the status is set to "pending-fulfillment".

Claims are approved from the claims detail page.

Once a claim is "pending-fulfillment" (approved) it shows up on the "Pending Export" screen.

Once a claim is exported successfully it is marked as "fulfilled"

------------------------------

## Fulfilled

A claim is fulfilled when it is successfully exported as excel file.

------------------------------

## Expired
* Default Expiration - 45 days from time of application approval
* Expiring Soon - rebate claims expiring in the next 14 days
* Expired Recently - rebate claims having expired in the last 14 days

------------------------------

# Denial Reasons
Hello, Unfortunately, since you did not respond to our requests for additional information we will have to deny this rebate. We encourage you to apply again if you are still interested in the program and funds are still available. Thank you.

Hello, per our phone conversation on 01-07-13.  I will deny this older application and then you may reapply.          

Per your email on 02-28-12, I will deny this application because of the deadline and focus on the newer one.  Thank you.  

Unfortunately, toilet purchased does not qualify for the rebate. 

------------------------------

# Changes

## Removed rebate_id from claims table
Claim previously had one Rebate.
Application already has one Rebate
and Claim belongs to Application so
we're removing the rebate_id from the claims
table until we find a good reason to put it back.

## Removed applicant_id from claims table
Applicant belongs to applicant and claim belongs
to application so we shouldn't need the applicant_id.

## Removed submitted_by_id
This submitted_by_id was null if the claims application was submitted by
and applicant and not an admin.

If the claim.application was submitted by an admin, they were copying the app.submitted_by_id (now admin_id) over to the claim. They aren't really using this anywhere except the claims index page where they show submission type (online or staff [staff person name]). I'm going to assume this isn't really important and just remove it.

In the old app, admins can't create claims at all and there is no reason to believe it's important to know which admin created the claim.

## Submitted At

An "empty" claim is created when the application is submitted. The applicant has to complete the claim. When the claim is completely done and has reached final submission by the applicant, the claim submitted_date is set.

* RebateClaims@index
    - orders claim on submitted at
* Model\RebateClaim@submit_new_claim
    - if status 'not-claimed', status is new
    - set submitted_at to now
        * controllers/rebate_center@process_claim

------------------------------

# Misc

## Approved/Fulfilled/Pending Fulfillment
When approved, rebate_claim->approve is called.
Just a few lines after approve is called
rebate_claim->mark_for_fulfillment is called
which changes the status from 'approved' to 'pending-fulfillment'

When ams_xml_export controller exports the claims
it changes 'pending-fulfillment' to 'fulfilled'.

When xml is exported and the file fails to write,
it is switched back to 'pending-fulfillment'


------------------------------