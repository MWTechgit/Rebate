<template>

    <div>

        <h4>Give Us Your Feedback</h4>


        <BFormGroup label-cols-sm="7" label="How did you learn about this program?">
            <BFormSelect
                :name="`${prefix}_reference_type_id`"
                :value="reference.reference_type_id"
                @input="v=>input(v, 'reference.reference_type_id')"
                :options="references"
                text-field="type"
                value-field="id"
                required
                :state="states['reference.reference_type_id']">
                <template slot="first">
                    <option :value="null" disabled>-- Select an option --</option>
                </template>
            </BFormSelect>
        </BFormGroup>

        <BFormGroup v-if="showMoreInput" label-cols-sm="7" :label="showMoreInput">
            <BFormInput
            :name="`${prefix}_info_response`"
            :value="reference.info_response"
            @input="v=>input(v,'reference.info_response')"
            required
            :state="states['reference.info_response']"></BFormInput>
        </BFormGroup>

        <BFormGroup label-cols-sm="7">
            <template v-slot:label>
                Please tell us why getting a WaterSense&copy; toilet matters.
            </template>
            <BFormTextarea
                :name="`${prefix}_watersense`"
                :value="applicant.watersense"
                @input="v=>input(v, 'applicant.watersense')"
                placeholder="It matters because ..."
                rows="3"
                max-rows="6"
                required
                :state="states['applicant.watersense']"
            ></BFormTextarea>
            <spinner v-bind:busy="loading" scale=1></spinner>
        </BFormGroup>


    </div>

</template>

<script>
    import {BFormGroup, BFormInput, BFormSelect, BFormTextarea} from 'bootstrap-vue';
    import axios from 'axios';
    import {
        Applicant,
        Reference
    } from '../Classes';
    import Spinner from './Spinner.vue';
    import * as Toasted from './../toasted';
    import _find from 'lodash/find';
    import _isNil from 'lodash/isNil';

    export default {

        name: 'StepFour',

        components: { Spinner, BFormGroup, BFormInput, BFormSelect, BFormTextarea},
        mixins: [ ],
        props: {

            applicant: {
                type: Object,
                default: () => new Applicant()
            },
            reference: {
                type: Object,
                default: () => new Reference()
            },
            referencesEndpoint: {
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
                references: [],
                loading: false,
            }
        },
        computed: {
            chosenRef() {
                return _find(this.references, {id: parseInt(this.reference.reference_type_id) });
            },
            showMoreInput() {
                return (this.chosenRef||{}).info_text || false;
            }
        },
        created() {
            this.fetchReferences();
        },
        methods: {
            validate(objName, value ) {

                if ( objName === 'reference' ) {



                }

                else if ( objName === 'applicant' ) {


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
            fetchReferences() {

                if ( this.references.length ) {
                    return;
                }

                this.loading = true;
                axios.get(this.referencesEndpoint)
                    .then(response => {
                        this.references = response.data.data;
                    })
                    .catch(error => {
                        Toasted.catchError(error)
                    })
                    .finally( () => this.loading = false ); // @todo - Spinner is not appearing
            }
        }
    };

</script>