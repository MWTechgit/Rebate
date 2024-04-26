# Addresses
The old app has no addresses table so there are a lot of columns for addresses.

## Properties
Properties table has two different sets of address columns:
* actual property address columns
* ulitity provider address columns

## Applicants
Applicants have address fields for the actual property owner. These are the "authorized_" fields on the applicants table.

Note that the authorized_ fields do not contain info on the authorized property manager, they instead contain info on the actual property owner but they are only populated when the applicant is the authorized property manager and they need to put the info in for the actual property owner.

We're replacing all the authorized fields with an owner_id but instead of the owner_id being on the applicants table it will be on the properties table. Owners have an address. Owner may live at an address other than the property address.

`$application->property->owner`

Property owner is null if the applicant is the property owner so instead of referencing the property owner we will reference the property contact which will return either the applicant or an owner model, both of which implement a contactable interface.

```php
$application->getContact()->full_name
$application->getContact()->getAddress() (never null)
```

## Applications
Applications contain address fields for "remitto" address. These fields are populated when the applicant wants the rebate mailed to an address other than the property address.

## So what models will have an address?
* Property
    * Properties always have an address
* Owner (so they can be contacted for approval of rebate app)
    * Owners always have an address
* Application (when using remittance address)
    * Applications don't always have an address by reference
    * It is either the app.property.address or the app.address (remittance address)

## Remittance address
We removed the use_remittance_address column. Some people fill out the remittance address but forget to check "use remittance address". If they fill it out, let's just assume we should use it. If the remittance address is populated, we create an address record for the Application. Otherwise the application address is the application property address.