<template>

    <div>

        <div v-if="residential">


            <BFormGroup label-cols-sm="7" label="What is the name or your subdivision, development, or complex?">
                <BFormInput
                        :name="`${prefix}_subdivision_or_development`"
                        :value="property.subdivision_or_development"
                        @input="v=>input(v,'property.subdivision_or_development')"
                        :state="states['property.subdivision_or_development']"
                        ></BFormInput>
                <BFormText>Enter the sub-division, development or complex name on the property</BFormText>
            </BFormGroup>

            <BFormGroup label-cols-sm="7" label="How long have you lived at this address?">
                <BInputGroup append="Years">
                    <BFormInput type="number"
                            step=1
                            min=0
                            :name="`${prefix}_years_lived`"
                            :value="property.years_lived"
                            @input="v=>input(v,'property.years_lived')"
                            :state="states['property.years_lived']"
                            ></BFormInput>
                </BInputGroup>
            </BFormGroup>

            <BFormGroup label-cols-sm="7" label="How many people reside in the home(s)?">
                <BInputGroup append="Occupants">
                    <BFormInput type="number"
                            step=1
                            min=0
                            :name="`${prefix}_occupants`"
                            :value="property.occupants"
                            @input="v=>input(v,'property.occupants')"
                            :state="states['property.occupants']"
                            ></BFormInput>
                </BInputGroup>
            </BFormGroup>

            <BFormGroup label-cols-sm="7" label="How many full bathrooms are at the property?">
                <BInputGroup append="bathrooms">
                    <BFormInput type="number"
                            step=1
                            min=0
                            :name="`${prefix}_full_bathrooms`"
                            :value="property.full_bathrooms"
                            @input="v=>input(v,'property.full_bathrooms')"
                            :state="states['property.full_bathrooms']"
                            ></BFormInput>
                </BInputGroup>
            </BFormGroup>

            <BFormGroup label-cols-sm="7" label="How many half bathrooms are at the property?">
                <BInputGroup append="bathrooms">
                    <BFormInput type="number"
                            step=1
                            min=0
                            :name="`${prefix}_half_bathrooms`"
                            :value="property.half_bathrooms"
                            @input="v=>input(v,'property.half_bathrooms')"
                            :state="states['property.half_bathrooms']"
                            ></BFormInput>
                </BInputGroup>
            </BFormGroup>

        </div>

        <div v-else>

            <BFormGroup label-cols-sm="4" label="Name of business or institution">
                <BFormInput
                        :name="`${prefix}_subdivision_or_development`"
                        :value="property.subdivision_or_development"
                        @input="v=>input(v,'property.subdivision_or_development')"
                        :state="states['property.subdivision_or_development']"
                        ></BFormInput>
            </BFormGroup>

            <BFormGroup label-cols-sm="7" label="How many employees work at the property?">
                <BInputGroup append="Employees">
                    <BFormInput type="number"
                            step=1
                            min=0
                            :name="`${prefix}_occupants`"
                            :value="property.occupants"
                            @input="v=>input(v,'property.occupants')"
                            :state="states['property.occupants']"
                            ></BFormInput>
                </BInputGroup>
            </BFormGroup>

            <BFormGroup label-cols-sm="7" label="How many bathrooms are at the property?">
                <BInputGroup append="bathrooms">
                    <BFormInput type="number"
                            step=1
                            min=0
                            :name="`${prefix}_full_bathrooms`"
                            :value="property.full_bathrooms"
                            @input="v=>input(v,'property.full_bathrooms')"
                            :state="states['property.full_bathrooms']"
                            ></BFormInput>
                </BInputGroup>
            </BFormGroup>

            <BFormGroup label-cols-sm="7" label="How many toilets are at the property?">
                <BInputGroup append="toilets">
                    <BFormInput type="number"
                            step=1
                            min=0
                            :name="`${prefix}_toilets`"
                            :value="property.toilets"
                            @input="v=>input(v,'property.toilets')"
                            :state="states['property.toilets']"
                            ></BFormInput>
                </BInputGroup>
            </BFormGroup>

        </div>

        <BFormGroup label-cols-sm="7" label="What year was the property built?">
            <BFormInput type="number"
                step=1
                min="1900"
                :max="now"
                :name="`${prefix}_year_built`"
                :value="property.year_built"
                @input="v=>input(v,'property.year_built')"
                :state="states['property.year_built']"
                ></BFormInput>
        </BFormGroup>

        <hr />

        <BFormGroup>
            <BFormRadioGroup
                    :value="property.is_owner"
                    @input="v=>input(v,'property.is_owner')"
                    :checked="property.is_owner"
                    :options="$options.ownerOptions"></BFormRadioGroup>
        </BFormGroup>

        <div v-if="showOwner">

            <BFormText>
                If you are a property manager or management entity with authority and do not own the residence(s) or commercial building(s)
                where the toilets are being installed, provide the owner's information below. Please note that the owning entity will be
                contacted to verify approval:
            </BFormText>

            <BFormGroup label-cols-sm="4" label="Owner's First Name">
                <BFormInput :name="`${prefix}_first_name`" :value="owner.first_name" @input="v=>input(v,'owner.first_name')" required :state="states['owner.first_name']"></BFormInput>
            </BFormGroup>

            <BFormGroup label-cols-sm="4" label="Owner's Last Name">
                <BFormInput :name="`${prefix}_last_name`" :value="owner.last_name" @input="v=>input(v,'owner.last_name')" required :state="states['owner.last_name']"></BFormInput>
            </BFormGroup>

            <BFormGroup label-cols-sm="4" label="Owner's Email">
                <BFormInput type="email" :name="`${prefix}_email`" :value="owner.email" @input="v=>input(v,'owner.email')" required :state="states['owner.email']"></BFormInput>
            </BFormGroup>

            <BFormGroup label-cols-sm="4" label="Company">
                <BFormInput :name="`${prefix}_company`" :value="owner.company" @input="v=>input(v,'owner.company')" :state="states['owner.company']"></BFormInput>
            </BFormGroup>

            <BFormGroup label-cols-sm="4" label="Phone Number">
                <BFormInput type="tel" :name="`${prefix}_phone`" :value="owner.phone" @input="v=>input(v,'owner.phone')" required :state="states['owner.phone']"></BFormInput>
            </BFormGroup>

            <BFormGroup label-cols-sm="4" label="Mobile Number">
                <BFormInput type="tel" :name="`${prefix}_mobile`" :value="owner.mobile" @input="v=>input(v,'owner.mobile')" :state="states['owner.mobile']"></BFormInput>
            </BFormGroup>

            <InputAddress
                    florida-only
                    :value="owner.address"
                    :states="ownerStates"
                    required
                    @input="v => input(v,'owner.address')"></InputAddress>

        </div>

        <hr />

        <BFormGroup label-cols-sm="7" label="How many rebates do you require?">
            <BFormInput type="number"
                    :name="`${prefix}_rebate_count`"
                    :value="application.rebate_count"
                    @input="v=>input(v,'application.rebate_count')"
                    :state="states['application.rebate_count']"
                    min="1"
                    step="1"
                    :max="rebateLimit"
                    ></BFormInput>

        </BFormGroup>
            <BFormText>You may request a maximum of <strong>{{ rebateLimit }}</strong> rebates per qualifying applicant. Based on your property location,
                the max rebate value per toilet is <strong>${{ rebateValue }}</strong>
            </BFormText>

        <BFormGroup label-cols-sm="7" label="Is the toilet(s) that you are replacing original to the property structure?">
            <BFormRadioGroup
                    :name="`${prefix}_original_toilet`"
                    :value="property.original_toilet"
                    :checked="property.original_toilet"
                    @input="v=>input(v,'property.original_toilet')"
                    :state="states['property.original_toilet']"
                    :options="$options.originalOptions"></BFormRadioGroup>
        </BFormGroup>

        <BFormGroup label-cols-sm="7" label="How many gallons per flush do each of the toilets you are replacing use?">

            <BInputGroup append="Gallons" class="mb-2">
                <BInputGroupPrepend is-text>
                    <input type="radio" name="gallons" aria-label="Check for entering gallons" v-bind:value="true" v-model="gallons_checked">
                </BInputGroupPrepend>
                <BFormInput
                    :name="`${prefix}_gallons_per_flush`"
                    :value="property.gallons_per_flush"
                    @input="v=>input(v,'property.gallons_per_flush')"
                    placeholder="List per toilet. Eg 2.5, 2.1, 5"
                    :state="states['property.gallons_per_flush']"
                ></BFormInput>
            </BInputGroup>

            <BInputGroup>
                <BInputGroupPrepend is-text>
                    <input type="radio" name="gallons" :disabled="forceGallons" v-bind:value="false" v-model="gallons_checked">
                </BInputGroupPrepend>
                <BFormInput readonly :disabled="forceGallons" value="Unsure"></BFormInput>
            </BInputGroup>

        </BFormGroup>


        <div id="applicant_residence_map">
            <!-- <img src="//maps.googleapis.com/maps/api/staticmap?center=plantation,fl&amp;zoom=11&amp;size=390x500&amp;key=AIzaSyCGg3sMbgQLGV7dSFZWLvSqTxR1CtmiGoY"> -->
        </div>


    </div>

</template>

<script>

    import axios from 'axios';

    import _get from 'lodash/get'
    import _pickBy from 'lodash/pickBy'
    import _mapKeys from 'lodash/mapKeys'

    import {
        Applicant,
        Property,
        Owner,
        Application,
        Partner
    } from '../Classes';
    import Spinner from './Spinner.vue';
    import InputAddress from './InputAddress.vue'
    import * as Toasted from './../toasted';
    import {BFormGroup, BFormInput, BInputGroup, BFormRadioGroup, BFormText, BInputGroupPrepend} from 'bootstrap-vue';

    export default {

        name: 'StepThree',

        multifamily: 'Multi-family',

        ownerOptions: [
            {
                value:1,
                text:"I own the property where the toilet(s) will be replaced."
            },
            {
                value:0,
                text: "I do not own the property, but confirm that I have anuthority to apply on behave of the owner or owning entity."
            }
        ],

        originalOptions: [
            {value:'Yes', text: 'Yes'},
            {value:'No', text: 'No'},
            {value:'Maybe', text: 'Unsure'},
        ],

        components: { Spinner, InputAddress, BFormGroup, BFormInput, BInputGroup, BFormRadioGroup, BFormText, BInputGroupPrepend },
        mixins: [ ],
        props: {
            application: {
                type: Object,
                default: () => new Application()
            },
            owner: {
                type: Object,
                default: () => new Owner()
            },
            property: {
                type: Object,
                required: true,
                default: () => new Property()
            },
            partner: {
                type: Object,
                required: true,
                default: () => new Partner()
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
                loading: false,
                gallons_checked: false,
            }
        },
        computed: {
            rebateName() {
                return _get(this.partner, 'rebate.name');
            },
            rebateValue() {
                return _get(this.partner, 'rebate.value');
            },
            rebateLimit() {
                if ( this.multifamily ) {
                    return 5;
                }
                if ( this.residential ) {
                    return 2;
                }
            },
            multifamily() {
                return this.property.building_type === this.$options.multifamily;
            },
            residential() {
                return this.property.property_type_group === 'residential';
            },
            showOwner() {
                return parseInt(this.property.is_owner) ? false : true;
            },
            isOwner() {
                return !this.showOwner;
            },
            ownerStates() {
                return _mapKeys( _pickBy(this.states, (v,k) => k.startsWith('owner.address.') ), (v,k) => k.replace('owner.address.') );
            },
            now() {
                return ( new Date() ).getFullYear();
            },
            forceGallons() {
                return this.property.original_toilet !== 'Yes';
            }
        },
        watch: {
            showOwner( val ) {
                if ( !val ) {
                    // clear out owner data so it's not sent through the API
                    this.$emit(`update:owner`, new Owner());
                }
            },
            gallons_checked( val ) {
                this.$emit('update:gallons_checked', val);
            }
        },
        created() {

        },
        methods: {

            validate(objName, value ) {

                if ( objName === 'property' ) {
                    value.is_owner = parseInt(value.is_owner) ? 1 : 0;
                }

                else if ( objName === 'applicant' ) {

                }

                return value;

            },


            input(value, fullkey) {

                if ( this.rebateLimit && this.application.rebate_count > this.rebateLimit ) {
                    Toasted.showError('You may request a maximum of ' + this.rebateLimit + ' rebates.');
                    this.states['application.rebate_count'] = false;
                    this.application.rebate_count = this.rebateLimit;
                }

                var parts = fullkey.split('.');

                var cls = parts[0];
                var key = parts[1];

                var v = {...this[cls]};

                v[key] = value;

                v = this.validate(cls, v);

                this.$emit(`update:${cls}`, v);
                this.$emit('change',fullkey);
            }
        }
    };

</script>