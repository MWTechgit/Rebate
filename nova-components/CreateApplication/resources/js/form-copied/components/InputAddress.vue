<template>

    <div class="address-template">

        <BFormGroup label-cols-sm="4" label="Address">
            <BFormInput
                placeholder="Street address and number"
                :name="`${prefix}_line_one`"
                :value="value.line_one"
                :state="states.line_one"
                @input="v=>input(v,'line_one')"
                required
                ></BFormInput>
        </BFormGroup>

        <BFormGroup label-cols-sm="4" label="">
            <BFormInput
                placeholder="Unit Number"
                :name="`${prefix}_line_two`"
                 :value="value.line_two"
                 :state="states.line_two"
                 @input="v=>input(v,'line_two')"
                 ></BFormInput>
        </BFormGroup>

        <BFormGroup label-cols-sm="4" label="City">
            <BFormInput
                    placeholder="City"
                    :name="`${prefix}_city`"
                    :value="value.city"
                    @input="v=>input(v,'city')"
                    :state="states.city"
                    ></BFormInput>
        </BFormGroup>

        <BFormGroup label-cols-sm="4" label="State">
            <BFormSelect v-if="!floridaOnly"
                    placeholder="-- Select a state --"
                    :name="`${prefix}_state`"
                    :value="value.state"
                    @input="v=>input(v,'state')"
                    :options="stateOptions"
                    :state="states.state">
                <template slot="first">
                    <option :value="null" disabled>-- Select a state --</option>
                </template>
            </BFormSelect>
            <BFormInput v-else disabled readonly value="Florida"></BFormInput>
        </BFormGroup>

        <BFormGroup label-cols-sm="4" label="Zip Code">
            <BFormInput  :name="`${prefix}_postcode`" :value="value.postcode" @input="v=>input(v,'postcode')" :states="states.postcode"></BFormInput>
        </BFormGroup>


    </div>

</template>

<script>

    import usa_states from '../data/usa-states';
    import {BFormGroup, BFormInput, BFormSelect} from 'bootstrap-vue';
    import {Address} from '../Classes';

    export default {

        name: 'InputAddress',

        usa_states: usa_states,

        components: {BFormGroup, BFormInput, BFormSelect},

        props: {
            value: {
                type: Object,
                default: () => new Address()
            },
            floridaOnly: {
                type: Boolean,
                default: false
            },
            states: {

            },
            required: {
                type: Boolean,
                default: false
            },
            allowForeign: {
                type: Boolean,
                default: false,
            }
        },
        computed: {
            stateOptions()
            {
                if (this.allowForeign) {
                    return {
                        '': '--',
                        ...this.$options.usa_states
                    }
                } else {
                    return this.$options.usa_states;
                }
            }
        },
        data() {
            return {
                prefix: (Math.random()*1e32).toString(36)
            }
        },
        mounted() {
            if ( this.floridaOnly ) {
                this.input('FL', 'state');
            }
        },
        methods: {
            input(value, key) {
                var v = {...this.value};
                v[key] = value;

                if ( this.floridaOnly ) {
                    v.state = 'FL';
                }

                this.$emit('input', v);
                this.$emit(`update:${key}`, value);
            }
        }
    };

</script>