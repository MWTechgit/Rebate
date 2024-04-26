<template>

    <div>

        <BFormGroup label-cols-sm="4" label="First Name">
            <BFormInput :name="`${prefix}_first_name`" :value="applicant.first_name" @input="v=>input(v,'applicant.first_name')" required :state="states['applicant.first_name']"></BFormInput>
        </BFormGroup>

        <BFormGroup label-cols-sm="4" label="Last Name">
            <BFormInput :name="`${prefix}_last_name`" :value="applicant.last_name" @input="v=>input(v,'applicant.last_name')" required :state="states['applicant.last_name']"></BFormInput>
        </BFormGroup>

        <BFormGroup label-cols-sm="4" label="Email">
            <BFormInput type="email" :name="`${prefix}_email`" :value="applicant.email" @input="v=>input(v,'applicant.email')" required :state="states['applicant.email']"></BFormInput>
        </BFormGroup>

        <BFormGroup label-cols-sm="4" label="Confirm your Email">
            <BFormInput type="email"
                    :name="`${prefix}_email_confirmation`"
                    :value="applicant.email_confirmation"
                    @input="v=>input(v,'applicant.email_confirmation')"
                    required
                    :state="states['applicant.email_confirmation']"
                    ></BFormInput>
        </BFormGroup>

        <BFormGroup label-cols-sm="4" label="Phone Number">
            <BFormInput type="tel" :name="`${prefix}_phone`" :value="applicant.phone" @input="v=>input(v,'applicant.phone')" required :state="states['applicant.phone']"></BFormInput>
        </BFormGroup>

        <BFormGroup label-cols-sm="4" label="Mobile Number">
            <BFormInput type="tel" :name="`${prefix}_mobile`" :value="applicant.mobile" @input="v=>input(v,'applicant.mobile')" :state="states['applicant.mobile']"></BFormInput>
        </BFormGroup>

        <hr />

        <BFormGroup label-cols-sm="4" label="Which provides your water service?">
            <BFormSelect
                :name="`${prefix}_partner_id`"
                :value="partner_id"
                @input="v=>$emit('update:partner_id', parseInt(v) )"
                :options="partners"
                required
                text-field="name"
                value-field="id"
                :state="states.partner_id">
                <template slot="first">
                    <option :value="null" disabled>-- Select a city/municipality --</option>
                </template>
            </BFormSelect>
            <spinner v-bind:busy="loading" scale=1></spinner>
        </BFormGroup>

        <BFormGroup label-cols-sm="4" label="What type of property is this?">
            <BFormSelect
                    :name="`${prefix}_property_type`"
                    :value="property.property_type"
                    @input="v=>input(v,'property.property_type')"
                    :options="$options.propertyTypes"
                    required
                    :state="states['property.property_type']"
                    >
                <template slot="first">
                    <option :value="null" disabled>-- Select a property type --</option>
                </template>
            </BFormSelect>
        </BFormGroup>

        <BFormGroup v-if="showBuildingTypes" label-cols-sm="4" label="Building Type">
            <BFormSelect
                    :name="`${prefix}_building_type`"
                    :value="property.building_type"
                    @input="v=>input(v,'property.building_type')"
                    :options="$options.buildingTypes"
                    required
                    :state="states['property.building_type']">
                <template slot="first">
                    <option :value="null" disabled>-- Select a building type --</option>
                </template>
            </BFormSelect>
        </BFormGroup>

        <BFormGroup label-cols-sm="4">
            <BFormRadioGroup
                    :name="`${prefix}_email_opt_in`"
                    :checked="applicant.email_opt_in"
                    :value="applicant.email_opt_in"
                    @input="v=>input(v,'applicant.email_opt_in')"
                    :options="$options.optInOptions"
                    :state="states['applicant.email_opt_in']"></BFormRadioGroup>
        </BFormGroup>



    </div>

</template>

<script>

    import _isNil  from 'lodash/isNil'
    import axios   from 'axios';
    import Spinner from './Spinner.vue';
    import * as Toasted from './../toasted';
    import {Applicant,Property}                  from '../Classes';
    import {BFormGroup, BFormInput, BFormSelect, BFormRadioGroup} from 'bootstrap-vue';

    export default {

        name: 'StepOne',

        RESIDENTIAL: 'Residential',

        optInOptions: [
            {
                value:1,
                text:"Yes! I'd like to hear about other emails and activities you have planned for conservation."
            },
            {
                value:0,
                text:"No, I prefer not to be notified of any other contests or promotions."
            }
        ],

        propertyTypes: [
            'Residential',
            'Commercial/Business',
            'Institutional',
            'Non-Profit',
        ],

        buildingTypes: [
            'Single Family',
            'Townhouse',
            'Condo',
            'Duplex/Quad',
            'CoOp',
            'Multi-family',
            'Mobile Home',
        ],

        components: { Spinner, BFormGroup, BFormInput, BFormSelect, BFormRadioGroup },
        mixins: [ ],
        props: {

            applicant: {
                type: Object,
                default: () => new Applicant()
            },
            property: {
                type: Object,
                default: () => new Property()
            },
            partner_id: {
                type: [Number,String],
            },
            partnersEndpoint: {
                type: String,
                requred: true
            },
            states: {
                type: Object,
                required: false,
                default: () => ({})     // @todo - State is not displaying
            }
        },
        data() {
            return {
                prefix: (Math.random()*1e32).toString(36),
                partners: [],
                loading: false,
            }
        },
        computed: {
            showBuildingTypes() {
                return this.property.property_type === this.$options.RESIDENTIAL;
            }
        },
        created() {
            this.fetchPartners();
        },
        methods: {

            validate(objName, value ) {

                if ( objName === 'property' ) {
                    if ( !this.showBuildingTypes ) {
                        // clear out the building tupe value
                        value.building_type = null;
                    }
                }

                else if ( objName === 'applicant' ) {

                    if ( !_isNil(value.email_opt_in) ) {
                        value.email_opt_in = parseInt(value.email_opt_in) ? 1 : 0;
                    }

                }

                return value;

            },


            input(value, fullkey) {

                var parts = fullkey.split('.');

                var cls = parts[0];
                var key = parts[1];

                var v = {...this[cls]};

                v[key] = value;

                v = this.validate(cls, v);

                this.$emit(`update:${cls}`, v);
                this.$emit('change',fullkey);
            },
            fetchPartners() {

                if ( this.partners.length ) {
                    return;
                }

                this.loading = true;
                axios.get(this.partnersEndpoint)
                    .then(response => {
                        this.partners = response.data.data;
                    })
                    .catch(error => {
                        if ( (error||{}).response ) {
                            Toasted.catchError(error);
                        } else {
                            this.$emit('error');
                        }
                    })
                    .finally( () => this.loading = false ); // @todo - Spinner is not appearing
            }
        }
    };

</script>