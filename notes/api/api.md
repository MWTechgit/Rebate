## Description
Seeking a WordPress developer who has experience working with APIs in JavaScript.
We have a form on a WP website that needs to submit requests to an external API.

## Overview
Our site has a WP form on it that sends a request to an external application
used to manage toilet rebates. The form is used for submitting an application
for a toilet rebate.

The toilet rebate "create application" endpoint was written to conform to the messy POST request
received by the WP form. We would like to clean this up by having the request conform to the API
instead of the other way around. That being said, for simplicity in validating and processing
the form on the client side, we are taking a meet in the middle approach by having the form
conform more to the API but also providing "step" endpoints in the API to validate the form by step.

Your responsibilities:
* Populate some form labels/fields from API requests
* Make API requests from form field values
* Maintain look and functionality of form

You will have direct access to the developer of the new API who can answer
any of your questions regarding the API.

API doc and more details will be provided when the job is accepted.

The WP form should look and function the same to the end user with a few minor differences.
All required fields should remain required. The only difference
is that validation will be done per step now rather than all at the end
and validation errors will show up below their respective fields.

Form URL: https://conservationpays.com/rebate-application/
**Do not make final submission on the form**
**You can complete all steps except step 5**
**Please make a local copy of the entire WP installation once you have credentials required to do so**

Any checkboxes like "use same address" that copy over information into
other fields should remain along with their functionality.

Below is a list of all the form fields and the key/values that should be used
to send the data over. If the data type (string, int, float, etc) isn't obvious,
(or is important) it will be specified. Otherwise it will be left out.

The format for the key below is as follows:
```
Form Label
    The JSON key to use (applicant.first_name) when sending data
    any additional information...
```

Note that the form on the WP site will need to implement some of its own
validation logic. In most cases (when fields match up to API), it can rely on
the API validation errors. If a field requires validation before hitting the API
it will be noted below.

The WP form will make requests to the API per step and will receive errors
in JSON format if there are any. Validation will be done per step.

If there is an error when the step is submitted, the step remains the same
and the validation errors should be shown below the matching invalid fields.

"Next" on step 1 makes a post request to:

`/api/steps/one`

"Next" on step 2

`/api/steps/two`

"Next" on step 3

`/api/steps/three`

"Next" on step 4

`/api/steps/four`

"Submit" on step 5

`/api/applications/submit`

Note the dot notation:
```
JSON key "applicant.first_name"
means provide the following JSON in your request...
{
    "applicant": {
        "first_name": "Jane",
        // other applicant.* fields here
        // other applicant.* fields here
        // other applicant.* fields here
    }
}
```

Please do not make actual submissions to the form on "step 5" on the
live site. Once you have the WP site cloned to your local environment
Justin will help you set it up so that you can test the form functionality
with the old API without actually writing to the API application DB.

----------------------------------------------------------------------------------------------------

## Step One - Fields
```
First Name
    applicant.first_name

Last Name
    applicant.last_name

Email Address
    applicant.email

Re-enter Email Address
    applicant.email_confirmation

Email Opt In
    applicant.email_opt_in
    default false
    currently this sends a long string, send 1 or 0

Phone Number
    applicant.phone

Mobile Number
    applicant.mobile

Which City/Municipality Provides Your Water Service?
    partner_id
    populate from GET `/api/partners/active` (see below)

What Type of Property is this?
    property.property_type
    API returns error if value is not one of:
    - 'Residential'
    - 'Commercial/Business'
    - 'Institutional'
    - 'Non-Profit'

Building Type
    property.building_type
    show if "property_type" is "Residential"
    API returns error if value is not one of:
        - 'Single Family'
        - 'Townhouse'
        - 'Condo'
        - 'Duplex/Quad'
        - 'CoOp'
        - 'Multi-family'
        - 'Mobile Home'
```

## Step One - Valid Request Example
Endpoint: `api/steps/one`
```json
{
    "applicant": {
        "first_name": "Jane",
        "last_name": "Doe",
        "email": "jane@example.com",
        "email_confirmation": "jane@example.com",
        "email_opt_in": 0,
        "phone": "(555) 555-5555)",
        "mobile": null
    },
    "partner_id": 3,
    "property": {
        "property_type": "Residential",
        "building_type": "Single Family"
    }
}
```

## Step One - Success Response
All steps will return the validated JSON data from the post request and status 200.
Step One will also return json data for the selected partner and the
`property.property_type_group`. You'll need this data in later steps.

Any property type that is not residential is in the "commercial" group.
The residential property type is in the "residential" group.

```json
{
    "applicant": {
        "first_name": "Jane",
        "last_name": "Doe",
        "email": "jane@example.com",
        "email_opt_in": 0,
        "phone": "(555) 555-5555)",
        "mobile": null
    },
    "partner_id": 3,
    "property": {
        "property_type": "Residential",
        "property_type_group": "residential",
        "building_type": "Single Family"
    },
    "partner": {
        "id": 3,
        "name": "North Jake",
        "rebate": {
            "id": 16,
            "name": "HET Toilet Rebate",
            "value": "100"
        }
    }
}
```

## Step One - Error Response
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "applicant.first_name": [
            "The First Name field is required."
        ],
        "applicant.last_name": [
            "The Last Name field is required."
        ],
        "applicant.email": [
            "The Email field is required."
        ],
        "applicant.email_opt_in": [
            "The Email Opt In field is required."
        ],
        "applicant.phone": [
            "The Phone field is required."
        ],
        "partner_id": [
            "The City/Municipality field is required."
        ],
        "property.property_type": [
            "The Property Type field is required."
        ]
    }
}
```

Further error response examples will not be provided as all error
responses follow the same format. Any invalid field shows up as a key
in the "errors" object. The key will have an array value of error messages.
There can be multiple error messages, but you only need to output the first
error message in the array.

If the returned JSON data key does not directly match a field in the form,
you are responsible for parsing the data and displaying it below
the appropriate field or field group. Addresses are bit of an exception to this.

Addresses have form fields for:
* street number
* street direction
* street name
* street type

I would prefer we replace all of these fields with just "address line one"
and then replace "apartment/unit number" with "address line two" on the WP form.

This makes more sense because the JS request needs to combine all these fields
anyway and then send them as `"model.address.line_one"` and then "`model.address.line_two`".
Where "model" is the resource the address belongs to.

I'm not the client though, I'm the developer building the API so you'll have to ask
if those fields can just be swapped for two simple fields.

If you can't swap them, just output any error message received for "model.address.line_one"
or "model.address.line_two" beneath the group of fields, so not per field. Splitting those errors
out and showing them directly under the specific field would be too much work for no reason.

If you find any other situation where the above applies even if it doesn't come to addresses,
just use your best judgment to get the errors displayed without it being too complex.
We don't need to be too picky about how errors are displayed as long as the display is reasonable.

----------------------------------------------------------------------------------------------------

## Step Two - Fields
```
---------------------------------------------------------------
Physical Address of Property
---------------------------------------------------------------
Street Number + Street Direction + Street Name + Street Type
    property.address.line_one (combine the 4 fields and send as one)
    number, street name, and street type are required

Apartment/Unit Number
    property.address.line_two

City
    property.address.city

State
    property.address.state

Zip
    property.address.postcode

---------------------------------------------------------------
Water Utility Billing Information
---------------------------------------------------------------
Street Number + Street Direction + Street Name + Street Type
    utility_account.address.line_one (use combined fields as value)

Apartment/Unit Number
    utility_account.address.line_two

City
    utility_account.address.city

State
    utility_account.address.state

Zip
    utility_account.address.postcode

---------------------------------------------------------------
Mailing Address
---------------------------------------------------------------
NOTE:
If any one of these fields have a value, they are all required (except street direction).
The API will validate the required fields for you. It will also take care of requiring
all of them if one them is filled out. Just use the API error response.

The API completely ignores the "select box if..." field. If the address is present, it is
assumed they want the rebate mailed to a different address than the property address.
The box should remain on the form so they understand the purpose of the fields below it.

Street Number + Street Direction + Street Name + Street Type
    application.address.line_one (combine fields)
    number, street name, and street type are required

Apartment/Unit Number
    application.address.line_two
    nullable

City
    application.address.city
    required

State
    application.address.state

Zip
    application.address.postcode

---------------------------------------------------------------
Water Account Number
---------------------------------------------------------------
utility_account.account_number
```

## Step Two - Valid Request Example
Note: "application" fields may not be present in the request.
If they want the rebate mailed to another address, they will be present.
```json
{
    "property": {
        "address": {
            "line_one": "422 N Riverside Dr",
            "line_two": "Unit 322",
            "city": "Cityname",
            "state": "Florida",
            "postcode": "90210"
        }
    },
    "utility_account": {
        "account_number": "10458",
        "address": {
            "line_one": "422 N Riverside Dr",
            "line_two": "Unit 322",
            "city": "Cityname",
            "state": "Florida",
            "postcode": "90210"
        }
    },
    "application": {
        "address": {
            "line_one": "422 N Riverside Dr",
            "line_two": "Unit 322",
            "city": "Cityname",
            "state": "Florida",
            "postcode": "90210"
        }
    }
}
```
----------------------------------------------------------------------------------------------------

## Step Three - Fields
```
Available Rebates - HET Toilet Rebate
    - "HET Toilet Rebate" is a dynamic label
    - use stepOneResponse.partner.rebate.name

Max rebate value per toilet: (value)
    - the value is dynamic
    - use stepOneResponse.partner.rebate.value

Rebate Quantity Select
    application.rebate_count

show if "step 1 property type" is "Residential"
    Sub-division, development or complex name
        property.subdivision_or_development
    How long have you lived at this address? (in years)
        property.years_lived
    How many people reside in the home(s)?
        property.occupants
    Number of full bathrooms?
        property.full_bathrooms
    Number of half bathrooms?
        property.half_bathrooms
endif

show if "step 1 property type" is in [Commercial/Business, Instituional, Non-Profit]
    Name of business or institution
        property.subdivision_or_development
    How many employees work in the building?
        property.occupants
    Number of bathrooms?
        property.full_bathrooms
    Number of Toilets?
        property.toilets
endif

Do you own the residence(s)?
    omitted from API request

show if "own residence" true
    First Name
        owner.first_name
    Last Name
        owner.last_name
    Email
        owner.email
    Company
        owner.company
    Phone
        owner.phone
    Mobile
        owner.mobile
    Street Address
        owner.address.line_one
    City
        owner.address.city
    State
        owner.address.state
    Post Code
        owner.address.postcode
endif

Year built?
    property.year_built
Is the toilet(s) that you are replacing original to the home/structure?
    property.original_toilet (Yes, No, Maybe)
Gallons/Unsure
    omitted from API request
    forced to "gallons" if original toilet not yes (WP form validatation)
How many gallons per flush does/do the toilet(s) you are replacing use? (The old toilet(s). Please list per toilet.)
    property.gallons_per_flush
    required if "gallons" (WP Form validation)
```

## Step Three - Request Attributes
Note that the request attributes vary depending on the property type
and what form fields are being displayed.
```
{
    "application": {
        "rebate_count": string|int
    },
    "property": {
        "property_type_group": string - use stepOneResponse.property.property_type_group,
        "subdivision_or_development": string,
        "years_lived": null|string|int, (residential prop types)
        "year_built": string|int,
        "original_toilet": string, (Yes, No, Maybe)
        "gallons_per_flush": string,
        "occupants": string,
        "full_bathrooms": int|string,
        "half_bathrooms": null|int, (residential prop type)
        "toilets": null|string (commercial prop types)
    },
    // owner is optional
    "owner": {
        "first_name": string,
        "last_name": string,
        "email": string,
        "company": null|string,
        "phone": string,
        "mobile": null|string,
        "address": {
            "line_one": string,
            "line_two": null|string,
            "city": string,
            "state": string,
            "postcode": string
        }
    }
}
```

----------------------------------------------------------------------------------------------------

## Step Four - Fields
```
How Did You Learn About This Program?
    Populate from GET "/api/reference-types"
    reference.reference_type_id

What website, event, etc.
    The label is determined by the reference_type.info_text.
    If the info_text is empty string, don't show this field
    reference.info_response

Let Us Feature You!
    applicant.feature_on_water_saver
```

## Step Four - Request Attributes
```
{
    "references": {
        "reference_type_id": string|int,
        "info_response": string|null
    },
    "applicant": {
        "feature_on_water_saver": bool
    }
}
```

----------------------------------------------------------------------------------------------------

## Step Five - Fields
```
By checking this box applicant is certifying that she/he agrees with the Broward Water Partnership High-Efficiency Toilet (HET) Rebate Program Terms and Conditions.
    Require validation by WP Form
    Don't send to API.

When click Submit
    * Send JSON Post Request `api/submissions`
    * Success response will be status 201, no data will be sent back
        * Notify the applicant that the application was successfully submitted
          and that they will receive a confirmation email about the status of their application.
    * If there was an error give error message...
        "There was a problem processing your application. Please contact ConservationPays@Broward.org"
```

You'll use the data from the steps to build one big request and properly nest the attributes.
Send the `stepOneResponse.property.rebate.id` over as `rebate_id`. Note that the request may not
include all of the following data. Some fields are not required. For example, the entire "owner" data
won't be present in the request if the applicant is the property owner. The application only has
an address if they want the rebate mailed to an address other than the property address.
```json
{
    "rebate_id": 5,
    "applicant": {
        "first_name": "Jane",
        "last_name": "Doe",
        "email": "jane@example.com",
        "phone": "(555) 555-5555)",
        "mobile": null,
        "email_opt_in": 0,
        "feature_on_water_saver": 1
    },
    "utility_account": {
        "account_number": "10458",
        "address": {
            "line_one": "422 N Riverside Dr",
            "line_two": "Unit 322",
            "city": "Cityname",
            "state": "Florida",
            "postcode": "90210"
        }
    },
    "property": {
        "property_type": "Residential",
        "building_type": "Single Family",
        "property_type_group": "residential",
        "subdivision_or_development": "Foo Divison",
        "years_lived": 12,
        "year_built": 1908,
        "original_toilet": "Yes",
        "gallons_per_flush": "2",
        "occupants": "1 - 8",
        "full_bathrooms": 4,
        "half_bathrooms": 2,
        "address": {
            "line_one": "422 N Riverside Dr",
            "line_two": "Unit 322",
            "city": "Cityname",
            "state": "Florida",
            "postcode": "90210"
        }
    },
    "owner": {
        "first_name": "Jane",
        "last_name": "Doe",
        "email": "jane@example.com",
        "company": null,
        "phone": "(555) 555-5555",
        "mobile": null,
        "address": {
            "line_one": "222 E Oceanside Dr",
            "line_two": "Unit 7",
            "city": "Fort Lauderdale",
            "state": "Florida",
            "postcode": 90210
        }
    },
    "application": {
        "rebate_count": 2,
        "address": {
            "line_one": "422 N Riverside Dr",
            "line_two": "Unit 322",
            "city": "Cityname",
            "state": "Florida",
            "postcode": "90210"
        }
    },
    "reference": {
        "reference_type_id": 1,
        "info_response": "http://google.com"
    }
}
```

----------------------------------------------------

# API "GET" RESOURCES
## Active Partners
Endpoint `api/partners/active`  
Use this endpoint to populate partners in step1
## Reponse
```json
{
    "data": [
        {
            "id": 3,
            "name": "North Jake",
            "rebate": {
                "id": 16,
                "name": "HET Toilet Rebate",
                "value": "100"
            }
        },
        {
            "id": 11,
            "name": "Jimmyborough",
            "rebate": {
                "id": 10,
                "name": "HET Toilet Rebate",
                "value": "42"
            }
        },
        // more
    ]
}
```

## Reference Types
Endpoint `api/reference-types`  
Use this endpoint to populate fields in step 4
## Response
```json
{
    "data": [
        {
            "id": 1,
            "type": "Website",
            "info_text": "What website?"
        },
        {
            "id": 2,
            "type": "Utility bill message",
            "info_text": null
        },
        // more
    ]
}
```