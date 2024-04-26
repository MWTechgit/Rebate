<template>

    <div>

        <h4>Physical Address of Property</h4>

        <InputAddress
                florida-only
                :value="property.address"
                :states="propStates"
                required
                @input="v => input(v,'property.address')"></InputAddress>

        <hr />

        <BFormCheckbox v-model="showBillingAddress" v-bind:value="true" v-bind:unchecked-value="false">
            Use a different water utility billing address
        </BFormCheckbox>
        <BFormText>
            Enter your biling address where you receive any correspondance with your water utility if it is different from the physical address of the property.
        </BFormText>

        <div v-if="showBillingAddress" class="mt-4">

            <h4>Water Utility Billing Address</h4>

            <InputAddress
                    florida-only
                    :value="utility_account.address"
                    :states="utilStates"
                    :required="showBillingAddress"
                    @input="v => input(v,'utility_account.address')"></InputAddress>
        </div>

        <hr />

        <BFormCheckbox v-model="showMailingAddress" v-bind:value="true" v-bind:unchecked-value="false">
            Use a different mailing address
        </BFormCheckbox>
        <BFormText>
            Enter your mailing address where you would like to receive your check if it is different from the physical address of the property entered above.
        </BFormText>

        <div v-if="showMailingAddress" class="mt-4">

                <h4>Mailing Address</h4>

                <InputAddress
                    allow-foreign
                    :value="application.address"
                    :states="appStates"
                    :required="showMailingAddress"
                    @input="v => input(v,'application.address')"></InputAddress>

        </div>

        <hr />

        <BFormGroup label-cols-sm="4" label="Water Account Number">
            <BFormInput
                    :name="`${prefix}_account_number`"
                    :value="utility_account.account_number"
                    @input="v=>input(v,'utility_account.account_number')"
                    :state="states['utility_account.account_number']"
                    ></BFormInput>
        </BFormGroup>

    </div>

</template>

<script>

    import axios    from 'axios';
    import _mapKeys from 'lodash/mapKeys'
    import _pickBy  from 'lodash/pickBy'
    import {
        Address,
        Application,
        Property,
        UtilityAccount
    } from '../Classes';

    import Spinner from './Spinner.vue';
    import InputAddress from './InputAddress.vue';
    import {BFormGroup, BFormInput, BFormText, BFormCheckbox} from 'bootstrap-vue';

    export default {

        name: 'StepTwo',

        components: { Spinner, InputAddress, BFormGroup, BFormInput, BFormText, BFormCheckbox },
        mixins: [ ],
        props: {

            application: {
                type: Object,
                default: () => new Application()
            },
            property: {
                type: Object,
                default: () => new Property()
            },
            utility_account: {
                type: Object,
                default: () => new UtilityAccount()
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
                showMailingAddress: false,
                showBillingAddress: false
            }
        },
        watch: {
            showBillingAddress( val ) {
                if ( val ) {
                    // toggling it on. Clear the values.
                    this.billingAddressTo(new Address());
                } else if ( !val ) {
                    // copy from property
                    this.billingAddressTo(this.property.address);
                }
            }
        },
        computed: {

            appStates() {
                return _mapKeys( _pickBy(this.states, (v,k) => k.startsWith('application.address.') ), (v,k) => k.replace('application.address.', '') );
            },
            propStates() {
                return _mapKeys( _pickBy(this.states, (v,k) => k.startsWith('property.address') ), (v,k) => k.replace('property.address.', '') );
            },
            utilStates() {
                return _mapKeys( _pickBy(this.states, (v,k) => k.startsWith('utility_account.address') ), (v,k) => k.replace('utility_account.address.', '') );
            }
        },
        created() {

        },
        methods: {
            billingAddressTo( address ) {
                let copy = { ...this.utility_account };
                copy.address = {...address};
                this.$emit(`update:utility_account`, copy);
            },
            validate(objName, value ) {

                if ( objName === 'property' ) {

                    if ( !this.showBillingAddress ) {
                        // Also update the billing address
                        this.billingAddressTo(value.address);
                    }

                }

                else if ( objName === 'application' ) {

                }

                else if ( objName === 'utility_account' ) {

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
            }
        }
    };

</script>