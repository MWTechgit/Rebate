# Properties

## Changes
* Replaced `own_residence` and `own_structure` with ownership_type. `ownership_type` can be 'owner' or 'manager'. Owner owns property. Manager is the authorized property manager.
* Removing ownership_type, can check if owner is null
* Changed `number_of*` fields to `$field_name`
* Changed `how_many_residents` to `occupants` (merged)
* Changed `how_many_employees` to `occupants` (merged)
* Changed `how_long_at_residence` to `years_lived`
* Moved a bunch of these fields over from the applicants table
