export class Address {
    constructor ( data ) {
        var data = data && typeof data === 'object' ? data : {};
        this.line_one = data.line_one ;
        this.line_two = data.line_two ;
        this.city     = data.city     ;
        this.state    = data.state    ;
        this.postcode = data.postcode;
    }

    fake() {
        this.line_one = 'address1';
        this.city = 'mycity';
        this.state = 'FA';
        this.postcode = '12345';
        return this;
    }
}

export class Applicant {
    constructor ( data ) {
        var data = data && typeof data === 'object' ? data : {};
        this.first_name             = data.first_name             ;
        this.last_name              = data.last_name              ;
        this.email                  = data.email                  ;
        this.email_confirmation     = data.email_confirmation     ;
        this.email_opt_in           = data.email_opt_in           ;
        this.phone                  = data.phone                  ;
        this.mobile                 = data.mobile                 ;
        this.watersense = data.watersense ;
    }

    fake() {
        this.first_name         = 'Erin';
        this.last_name          = 'Lambroschini';
        this.email              = 'erinlambro@gmail.com';
        this.email_confirmation = this.email;
        this.phone              = '555-555-1212';
        this.email_opt_in       = false;
        return this;
    }
}

export class Partner {
    constructor ( data ) {
        var data = data && typeof data === 'object' ? data : {};
        this.id     = data.id;
        this.name   = data.name;
        this.rebate = new Rebate(data.rebate||{});
    }

}

export class Rebate {
    constructor ( data ) {
        var data = data && typeof data === 'object' ? data : {};
        this.id    = data.id;
        this.name  = data.name;
        this.value = data.value;
    }
}

export class Property {
    constructor ( data ) {
        var data = data && typeof data === 'object' ? data : {};
        this.address                    = new Address(data.address||{});
        this.property_type              = data.property_type             ;
        this.building_type              = data.building_type             ;
        this.property_type_group        = data.property_type_group       ;
        this.subdivision_or_development = data.subdivision_or_development;
        this.occupants                  = data.occupants                 ;
        this.full_bathrooms             = data.full_bathrooms            ;
        this.year_built                 = data.year_built                ;
        this.property_type_group        = data.property_type_group       ;
        this.toilets                    = data.toilets                   ;
        this.half_bathrooms             = data.half_bathrooms            ;
        this.years_lived                = data.years_lived               ;
        this.original_toilet            = data.original_toilet           ;
        this.gallons_per_flush          = data.gallons_per_flush         ;
        this.is_owner                   = data.is_owner;
    }

    fake() {
        this.address = (new Address()).fake();
        this.property_type = 'Residential';
        this.building_type = 'Townhouse';
        this.property_type_group        = 'residential'       ;
        this.subdivision_or_development = 'mycondos';
        this.occupants                  = 1;
        this.full_bathrooms             = 1;
        this.year_built                 = 1990;
        this.toilets                    = 1;
        this.half_bathrooms             = 1;
        this.years_lived                = 1;
        this.original_toilet            = 'Yes';
        this.gallons_per_flush          = '1.2, 2.5';
        this.is_owner                   = true;
        return this;
    }
}

export class Owner {
    constructor (data ) {
        var data = data && typeof data === 'object' ? data : {};
        this.first_name = data.first_name;
        this.last_name  = data.last_name ;
        this.email      = data.email     ;
        this.company    = data.company   ;
        this.phone      = data.phone     ;
        this.mobile     = data.mobile    ;
        this.address    = new Address(data.address||{});
    }
}

export class Application {
    constructor (data ) {
        var data = data && typeof data === 'object' ? data : {};
        this.rebate_count         = data.rebate_count        ;
        this.desired_rebate_count = data.desired_rebate_count;
        this.address              = new Address(data.address||{});
    }
}

export class Reference {
    constructor (data ) {
        var data = data && typeof data === 'object' ? data : {};
        this.id                = data.id || data.reference_type_id;
        this.reference_type_id = data.reference_type_id || data.id;
        this.info_response     = data.info_response;
        this.type              = data.type;
        this.info_text         = data.info_text;
    }
}

export class UtilityAccount {
    constructor (data ) {
        var data = data && typeof data === 'object' ? data : {};
        this.account_number         = data.account_number        ;
        this.address              = new Address(data.address||{});
    }
}