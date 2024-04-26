# Rebate Types
- No FKs

# Partners
- parent_id (self)
- slug =OMITTED=

# Rebates
- rebate_type_id
- partner_id 
- fy_year
- type =OMITTED=
- total_distributed =OMITTED=

# Rebate Applicants
- first_name
    * string 45
- last_name
    * string 45
- (left out) middle
- (left out) display_name
- business_name
    * =RENAMED to company
    * string 120
    * nullable
- email
    * string
    * nullable
- phone
    * string 15
    * nullable
- mobile
    * string 15
    * nullable
- own_residence
    * string 15
    * nullable
    * always yes/no
    * convert to boolean in refactor
    * leave as string for now
- own_structure
    * string 15 (yes/authorized)
    * "yes" if property owner
    * "authorized" if property manager
- how_many_residents
    * string 25
    * default 0
    * could be (1-10) in the future
    * for now is just an int but keep it as string
- how_many_employees
    * string 25
    * default 0
    * usually a range (1-10, 10-20, etc)
- how_long_at_residence
    * string 25
- note: all authorized fields are nullable string
- note: all authorized fields moved to owners table that belongs to property
- authorized_first_name
- authorized_last_name
- authorized_company
- authorized_email
- authorized_phone
- authorized_mobile
- authorized_address1
- authorized_address2
- authorized_city
- authorized_state
- authorized_postal_code
- feature_on_water_saver
    * boolean
    * default false
- email_opt_in
    * current: string (Y/N) and sometimes 0 !! :-(
    * =UPDATED=: boolean
    * default 0


## Note on "authorized" fields on Applicants table
Any authorized_* field on the old system DB does not actually contain info on the property manager (authorized property manager), it instead contains information on the actual owner of the home since they are submitting the application for that person.

This "person info" should go in its own table. This table will be called "owners" and instead of the information belonging to the applicant it will belong to the property. Applicants don't have owners, properties do.

Because property->owner could return an Owner or an Applicant, the Owner model will implement a Contactable interface that Applicant will also implement. That way we can always reference any property->getContact()->someField knowing something comes back. That will most likely mean every field
in the owners table will also exist in the applicants table.

# Rebate Claims
- integer unsigned "rebate_id"
- integer unsigned "applicant_id"
- string 45 "status"
- =SKIP= upc_, receipt_, product_
- boolean "skip_document_upload" default 0
- string 15 "awarded" =RENAMED= "amount_awarded"
- =X text "denial_reason"
- =X integer "transaction_by_id"
- =X datetime `transaction_at` nullable
- =X `denied_by_id` int(12) DEFAULT NULL,
- =X `denied_at` datetime DEFAULT NULL,
- =X `approved_by_id` int(12) DEFAULT NULL,
- =X `approved_at` datetime DEFAULT NULL,
- `submission_type` varchar(20) DEFAULT NULL,
- `submitted_at` datetime DEFAULT NULL,
- `submitted_by_id` int(12) DEFAULT NULL,
- `expire_notification_sent` int(3) NOT NULL DEFAULT '0',
- `mailed_at` datetime DEFAULT NULL,
- `post_marked_at` datetime DEFAULT NULL,
- `expires_at` datetime DEFAULT NULL,
- `expired_at` datetime DEFAULT NULL,
- `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
- `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
- `fy_year` int(4) DEFAULT '2015',

## Note on Claim Transactions
Should introduce new model/table
Model: Transaction
- type: (approved, denied)
- user_id: Takes place of columns
    * transaction_by_id (this isn't used in views)
    * denied_by_id
    * approved_by_id
- created_at: Takes place of columns
    * denied_at
    * approved_at
    * transaction_at
- description: Takes place of columns
    * denial_reason

## Claim submitted_by_id
- This is sometimes a user and sometimes an applicant

# Properties
- `id` int(12) NOT NULL AUTO_INCREMENT,
- `rebate_applicant_id` int(12) NOT NULL,
- `partner_id` int(12) NOT NULL,
- `building_type` varchar(25) DEFAULT NULL,
- `subdivision_or_development` varchar(45) DEFAULT NULL,
- `number_of_bathrooms` varchar(15) NOT NULL,
- `number_of_toilets` varchar(15) NOT NULL,
- `full_bathrooms` int(5) DEFAULT NULL,
- `half_bathrooms` int(5) DEFAULT NULL,
- `year_built` varchar(35) DEFAULT NULL,
- `utility_account_number` varchar(45) DEFAULT NULL,
- `address1` varchar(180) DEFAULT NULL,
- `address2` varchar(180) DEFAULT NULL,
- `street_number` varchar(15) NOT NULL,
- `street_direction` varchar(5) NOT NULL,
- `street_name` varchar(45) NOT NULL,
- `street_suffice` varchar(25) NOT NULL,
- `apartment_unit_number` varchar(10) NOT NULL,
- `city` varchar(45) DEFAULT NULL,
- `state` varchar(45) DEFAULT NULL,
- `state_abbrev` varchar(5) DEFAULT NULL,
- `postal_code` varchar(12) DEFAULT NULL,
- `country` varchar(65) DEFAULT NULL,
- `lat` varchar(25) DEFAULT NULL,
- `lng` varchar(25) DEFAULT NULL,
- `utility_address1` varchar(180) DEFAULT NULL,
- `utility_address2` varchar(180) DEFAULT NULL,
- `utility_street_number` varchar(15) NOT NULL,
- `utility_street_direction` varchar(5) NOT NULL,
- `utility_street_name` varchar(45) NOT NULL,
- `utility_street_suffice` varchar(25) NOT NULL,
- `utility_apartment_unit_number` varchar(10) NOT NULL,
- `utility_state` varchar(45) DEFAULT NULL,
- `utility_state_abbrev` varchar(5) DEFAULT NULL,
- `utility_city` varchar(45) DEFAULT NULL,
- `utility_postal_code` varchar(12) DEFAULT NULL,
- `utility_country` varchar(65) DEFAULT NULL,
- `original_toilet` varchar(15) DEFAULT NULL,
- `gallons_per_flush` varchar(255) DEFAULT NULL,
- `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
- `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

# Notes on Properties
Properties in the old system belonged to rebate_applicant
and rebate_application belonged to Property.
- rebate_applications.property_id
- properties.rebate_applicant_id

It's the same FK repeated in tables that are already related.
At first glance this doesn't seem necessary but it's possible
there is a good reason for it. For now we'll just have the property
belong to the application.


# Rebate Applications
- `id` int(12) NOT NULL AUTO_INCREMENT,
- `rebate_id` int(12) NOT NULL,
- `rebate_applicant_id` int(12) NOT NULL,
- `rebate_claim_id` int(12) unsigned DEFAULT NULL,
- `property_id` int(12) NOT NULL,
- `fy_year` int(4) DEFAULT '2017',
- `rebate_code` varchar(25) NOT NULL,
- `rebate_count` int(5) NOT NULL, (=OMITTED=)
- `rebate_count_exception` int(12) NOT NULL DEFAULT '0',
- `desired_rebate_count` int(12) NOT NULL DEFAULT '0',
- `previous_rebate_id` int(12) DEFAULT NULL,
- `previous_rebate_count` int(5) NOT NULL DEFAULT '0',
- `previous_rebate_code` varchar(25) DEFAULT NULL,
- `status` varchar(45) DEFAULT NULL,
- `application_type` varchar(45) NOT NULL,

=REPLACING= "what_","hoa","other" with new model
Model: Reference
Columns:
    -type: website, flyer, hoa, etc.
        * replaces "how_did_you_learn.."
    -source: what website, what flyer, what hoa etc.
        * replaces what_

- `how_did_you_learn_about_program` varchar(200) NOT NULL,
- `what_website` varchar(200) DEFAULT NULL,
- `what_flyer` varchar(200) DEFAULT NULL,
- `what_sp_event` varchar(200) DEFAULT NULL,
- `what_jjjs_event` varchar(200) DEFAULT NULL,
- `what_company` varchar(200) DEFAULT NULL,
- `what_display_location` varchar(200) DEFAULT NULL,
- `what_print_publication` varchar(200) DEFAULT NULL,
- `what_newspaper_publication` varchar(200) DEFAULT NULL,
- `what_event` varchar(200) DEFAULT NULL,
- `what_radio_station` varchar(200) DEFAULT NULL,
- `what_tv_station` varchar(200) DEFAULT NULL,
- `what_location` varchar(200) DEFAULT NULL,
- `hoa_name` varchar(200) DEFAULT NULL,
- `other` varchar(200) DEFAULT NULL,
- `denial_reason` text,
- `denial_moreinfo` text,
- `submitted_by_id` int(12) DEFAULT NULL,
- `transaction_by_id` int(12) DEFAULT NULL,
- `transaction_at` datetime DEFAULT NULL,
- `notification_sent` int(3) NOT NULL DEFAULT '0',
- `notification_sent_at` datetime DEFAULT NULL,
- `approved_by_id` int(11) DEFAULT NULL,
- `approved_at` datetime DEFAULT NULL,
- `use_remitto_address` tinyint(4) NOT NULL DEFAULT '0',
- `remitto_address1` char(35) DEFAULT NULL,
- `remitto_address2` char(35) DEFAULT NULL,
- `remitto_city` char(25) DEFAULT NULL,
- `remitto_state_abbrev` char(2) DEFAULT NULL,
- `remitto_postal_code` char(10) DEFAULT NULL,
- `remitto_country` char(20) DEFAULT NULL,
- `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
- `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,