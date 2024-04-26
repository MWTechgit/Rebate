## FISCAL YEAR
The current application is just using the current year as the fiscal year
at all times. Is this a mistake?

    Yes this is a mistake. Use the real fiscal year.

## EXPORT
Why so many formats?
What formats are actually used?

    We only need CSV or xls format. Forget everything else.

## Desired Rebate Count
What is this used for?

    This is just info for the admin to use. Admin asks approval from partner.

## Does assigning rebate partner parent partners actually work in the old system?

    Yes, implement this

## Previous/New Rebate codes
* This code looks dead. Can you please explain this feature?

    Sometimes the applicant selects the wrong partner when applying
    for a rebate. We have to be able to chnage the partner on an application.
    This is why the old app has "previous" rebate code columns.
    We probably don't need those columns but we do need to make required
    inventory adjustments when the partner is changed for an app.

## Rebates per partner per fiscal year
* Can partners have more than one rebate per year?
* If they do, how do you determine which rebate to use in the
  application form?
* It seems like if they have a current rebate with rebates remaining,
  they shouldn't be allowed to add another rebate. Otherwise, we don't know
  which rebate inventory to change.
* We could set the rebate as "active" or just pull any rebate that has remaining
  inventory, not caring which one we use.

    Partners shouldn't be able to have more than one active rebate in a fiscal year