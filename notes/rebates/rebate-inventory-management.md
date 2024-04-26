# Claiming/Releasing Rebates

The rebate quantity determines the number still available.
The rebate inventory is the initial amount available.

When an application is submitted we have to decrease the number
of available rebates for the rebate the application was applied for
even though the application isn't approved yet.

It appears that this is so that people don't apply for a rebate
and then aren't able to receive one because there aren't enough left.
I'm not sure why this is important because it still happens in
the old system and the denial reason explains to the applicant
that there isn't budget left for the rebate.

I guess it would save the admin time to not have to deny applications
that wouldn't have come in to begin with only to tell them no rebates
are available.

If the application is denied, we increase the rebates available
to make those rebates available again.

Note that there are two columns for rebate count
* `rebate_count`
* `desired_rebate_count`

It seems that the desired rebate count form field only shows up if the property type is commercial or multi family. I don't see the data being used anywhere except for display purposes.

For now we'll ignore the `desired_rebate_count` when it comes to calculating rebates available or the max amount to grant for a rebate. It doesn't look like that column is used in any calculations at all. I'm not sure how it is used.

When application submitted
* Decrease quantity by number of rebates requested `application.rebate_count`

When application approved
* Do nothing

When application denied
* Increase quantity by number of rebates requested `application.rebate_count`

When claim denied
* Deny the application (triggers when application denied logic)