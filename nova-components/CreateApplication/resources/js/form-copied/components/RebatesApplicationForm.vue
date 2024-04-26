<template>

    <div id="vue-rebates-application-main-wrapper">

    <b-alert
      v-model="showNoRebateCodeAlert"
      class="position-fixed fixed-top m-0 rounded-0 api-error"
      style="z-index: 2000;"
      variant="info"
      dismissible
    >
      Your application was submitted but we were unable to retrieve your rebate code.
      You should receive it in an email. If not, please contact us at {{ this.$options.contactInfo }}.

      <b-button variant="danger" @click="location.href = $options.contactUrl">Contact Us</b-button>
    </b-alert>

    <b-alert
      v-show="showApiErrorAlert"
      class="position-fixed fixed-top m-0 rounded-0 api-error"
      style="z-index: 2000;"
      variant="danger"
      dismissible
    >
      {{ apiErrorMessage || 'Sorry, we were unable to connect to the server.' }}. Please try again later or contact us at {{ this.$options.contactInfo }}.

      <b-button variant="danger" @click="location.href = $options.contactUrl">Contact Us</b-button>
    </b-alert>

    <FormWizard
        class="wizard"
        title="Rebate Application"
        subtitle="Submit an application for a toilet rebate"
        step-size="sm"
        @on-complete="completed"
        @on-loading="v => loading = v"
        @on-error="e => handleError(e)"
        v-show="!finished"
        ref="wizard">

        <TabContent title="CONTACT" :before-change="validateStep1"  ref="step1">

            <template v-if="admin">

                <BFormGroup label-cols-sm="4" label="Submitting By">
                    <BFormSelect
                        :name="`${$options.prefix}_submission_type`"
                        v-model="submissionTypeSelect"
                        required
                        :options="$options.submissionTypes"
                    ></BFormSelect>
                    <template slot="first">
                        <option :value="null" disabled>-- Select an option --</option>
                    </template>
                </BFormGroup>

                <BFormGroup v-if="showSubmissionTypeOther" label-cols-sm="7" label="Enter submission type">
                    <BFormInput
                        :name="`${$options.prefix}_submission_type_other`"
                        v-model="submissionTypeOther"
                        required></BFormInput>
                </BFormGroup>

                <hr>

            </template>

            <StepOne
                    :property.sync="property"
                    :applicant.sync="applicant"
                    :partner_id.sync="partner_id"
                    :partners-endpoint="endpoints.partners"
                    :states="states"
                    @error="apiError"
                    @change="resetState"></StepOne>
        </TabContent>

        <TabContent title="ADDRESS" :before-change="validateStep2" ref="step2">
            <StepTwo
                    :property.sync="property"
                    :utility_account.sync="utility_account"
                    :application.sync="application"
                    :states="states"
                    @change="resetState"></StepTwo>
        </TabContent>

        <TabContent title="DETAILS" :before-change="validateStep3" ref="step3">
            <StepThree
                    :property.sync="property"
                    :owner.sync="owner"
                    :application.sync="application"
                    :partner="partner"
                    :gallons_checked.sync="gallons_checked"
                    :states="states"
                    @change="resetState"></StepThree>
        </TabContent>

        <TabContent title="FEEDBACK" :before-change="validateStep4" ref="step4">

            <StepFour
                    :reference.sync="reference"
                    :applicant.sync="applicant"
                    :references-endpoint="endpoints.references"
                    :states="states"
                    @change="resetState"></StepFour>
        </TabContent>

        <TabContent title="SUBMIT" :before-change="validateStep5" ref="step5">

            <h4>Certification of Rebate Agreement - Release of Liability</h4>

            <p><strong>
                I hereby certify that I am the property owner of the property listed above or I have permission from the property owner. I have also provided the water account information including the account holder's contact information. I have read and will abide by the Broward Water Partnership High-Efficiency Toilet (HET) Rebate Program Terms and Conditions for the rebate for which I am requesting approval.
            </strong></p>

            <BFormCheckbox v-model="agreed"
                name="agree_terms"
                v-bind:value="true"
                v-bind:unchecked-value="false"
                :state="states.agreed"
                >

                By checking this box, the applicant is certifying that she/he agrees with the Broward Water Partnership High-Efficiency Toilet (HET) Rebate Program
                <strong><a href="https://conservationpays.com/apply/rebate-terms-and-conditions/" target="_blank">Terms and Conditions</a></strong>.
            </BFormCheckbox>

            <h4>Reminder</h4>
            <p>Do not purchase your toilet unless you have received your Approval Confirmation Notification. Toilets purchased before you have been approved may not be eligible for a rebate.</p>

        </TabContent>

    </FormWizard>

    <div v-if="finished" class="text-center">

        <h4>Application Submitted</h4>

        <p>Your application was successfully submitted. Soon, you will receive a confirmation email about your application status.</p>

        <b-button variant="primary" :href="$options.urlAfterSuccess">{{ $options.textAfterSuccess }}</b-button>


    </div>

    <b-modal ref="finishedModal" title="Thank you" size="lg" id="vue-rebates-application-modal" >
        <Finished
            :waitlist="waitlist"
            :rebateCode="rebate_code"
            :email="applicant.email"
            :name="[applicant.first_name, applicant.last_name].join(' ')"
        ></Finished>
    </b-modal>

    </div>

</template>

<script>



    import axios     from 'axios';
    import qs        from 'qs';
    import StepOne   from './StepOne.vue';
    import StepTwo   from './StepTwo.vue';
    import StepThree from './StepThree.vue';
    import StepFour  from './StepFour.vue';
    import Finished  from './Finished.vue';

    import {FormWizard, TabContent} from 'vue-form-wizard'
    import * as Toasted from './../toasted';
    import {BAlert, BModal, BButton, BFormCheckbox, BFormGroup, BFormSelect, BFormInput} from 'bootstrap-vue';

    import _mapValues from 'lodash/mapValues';
    import _each from 'lodash/each';
    import _keyBy from 'lodash/keyBy'
    import _get from 'lodash/get';

    import {hasLocalStorage} from './../utils'

    import {
        Address,
        Applicant,
        Application,
        Owner,
        Partner,
        Property,
        Reference,
        UtilityAccount
    } from '../Classes';

    const DEBUG = false;

    const ENDPOINTS = {

        submit: '/api/submissions', // POST
        step1: '/api/steps/one',   // POST
        step2: '/api/steps/two',   // POST,
        step3: '/api/steps/three', // POST,
        step4: '/api/steps/four',  // POST,

        partners: '/api/partners/active', // GET
        references: '/api/reference-types', // GET

    };

    const AJAX_DATA = typeof ajax_object !== 'undefined' ? ajax_object : {};

    const DOMAIN      = (AJAX_DATA||{}).api_domain || 'https://rebates.conservationpays.com';
    // const WP          = (AJAX_DATA||{}).ajax_url;
    // const CAPTCHA_KEY = (AJAX_DATA||{}).captcha_site_key;
    // const HAS_CAPTCHA = (AJAX_DATA||{}).has_captcha;
    // const ACTION      = (AJAX_DATA||{}).action;
    // const WP_AJAX     = WP + '?action=' + ACTION;

    export default {

        name: 'RebatesApplicationForm',

        faking: false,

        urlAfterSuccess : 'https://conservationpays.com/theres-more/',
        textAfterSuccess: 'Learn More about Water Conservation',
        contactInfo     : 'ConservationPays@Broward.org or 1-800-270-9794',
        contactUrl      : 'https://conservationpays.com/contact-us/',

        components: { FormWizard, TabContent, StepOne, StepTwo, StepThree, StepFour, Finished, BAlert, BModal, BButton, BFormCheckbox, BFormGroup, BFormSelect, BFormInput },
        mixins: [ ],

        submissionTypes: ['online', 'phone', 'email', 'mail', 'admin', 'testing', 'other'],

        prefix: (Math.random()*1e32).toString(36),

        props: {
            domain: {
                type: String,
                required: false,
                default: DOMAIN
            },
            admin: {
                type: Boolean,
                required: false,
                default: false
            }
        },
        computed: {
            endpoints() {
                return {
                    partners: this.domain + ENDPOINTS.partners,
                    references: this.domain + ENDPOINTS.references,
                };
            },
            showSubmissionTypeOther() {
                return this.submissionTypeSelect === 'other';
            },
            submission_type() {
                return this.showSubmissionTypeOther ? this.submissionTypeOther : this.submissionTypeSelect;
            }
        },
        data(){
            return {
                loaded         : false,
                loading           : false,
                agreed         : false,
                finished       : false,

                partner_id     : null,
                applicant      : new Applicant(),
                property       : new Property(),
                owner          : new Owner(),
                application    : new Application(),
                reference      : new Reference(),
                utility_account: new UtilityAccount(),
                partner        : new Partner(),
                states         : this.emptyStates(),

                waitlist       : null,
                rebate_code    : null,

                gallons_checked: false, // For Step 3. Can't access the refs?!

                submissionTypeOther  : null,
                submissionTypeSelect : null,

                showNoRebateCodeAlert: false,
                apiErrorMessage      : '',
                showApiErrorAlert    : true,

            };
        },
        mounted() {

            this.$options.faking && this.fake();

            this.loadFromStorage();
        },


        methods: {

            stateOf(obj,prefix) {
                return _mapValues( _keyBy( Object.keys(obj), k => prefix + '.'+k ), v => null );
            },
            nothing() {

            },
            emptyStates() {
                return {
                    ...this.stateOf(new Applicant(), 'applicant'),
                    ...this.stateOf(new Property(), 'property'),
                    ...this.stateOf(new Owner(), 'owner'),
                    ...this.stateOf(new Application(), 'application'),
                    ...this.stateOf(new UtilityAccount(),'utility_account'),
                    ...this.stateOf(new Reference(),'reference'),
                    partner_id: null,
                    agreed: null
                };
            },

            fake() {
                this.applicant = (new Applicant()).fake();
                this.property = (new Property()).fake();
                this.partner_id = 1;
            },
            clearErrors() {
                this.states = this.emptyStates();
            },
            resetState(key) {
                this.states[key] = null;
            },
            async validateStep1() {

                var data = qs.stringify({
                    applicant : this.applicant,
                    property  : this.property,
                    partner_id: this.partner_id
                });

                return axios.post(this.domain + ENDPOINTS.step1, data)
                    .then( response => {
                        var {applicant,partner,partner_id,property} = response.data;

                        this.partner    = new Partner(partner);
                        this.partner_id = parseInt(partner_id);

                        // Merge the data into our classes
                        property   && _each(property, (v,k) => this.property[k] = v);
                        applicant  && _each(applicant, (v,k) => this.applicant[k] = v);

                        // Clear out any state errors
                        this.clearErrors();
                        this.scrollToTop();

                        this.saveToStorage();

                        return true
                    })
                    .catch( err => {
                        DEBUG && console.warn('Got an error in step 1:', err);
                        return Promise.reject(err)
                    });
            },
            async validateStep2() {

                var data = qs.stringify({
                    application    : this.application,
                    property       : this.property,
                    utility_account: this.utility_account
                });

                return axios.post(this.domain + ENDPOINTS.step2, data)
                    .then( response => {

                        var {application,property,utility_account} = response.data;

                        // Merge the data into our classes
                        application     && _each(application, (v,k) => this.application[k] = v);
                        property        && _each(property, (v,k) => this.property[k] = v);
                        utility_account && _each(utility_account, (v,k) => this.utility_account[k] = v);

                        // Clear out any state errors
                        this.clearErrors();
                        this.scrollToTop();

                        this.saveToStorage();

                        return true
                    })
            },
            async validateStep3() {


                var data = {
                    application: this.application,
                    property   : this.property,
                };

                if ( !parseInt(this.property.is_owner)) {
                    data.owner = this.owner;
                }

                if ( this.gallons_checked && !this.property.gallons_per_flush ) {
                    this.showError('Enter gallons per flush for each toilet you are replacing.')
                    return false;
                }

                return axios.post(this.domain + ENDPOINTS.step3, qs.stringify(data))
                    .then( response => {

                        var {application,property} = response.data;

                        // Merge the data into our classes
                        application     && _each(application, (v,k) => this.application[k] = v);
                        property        && _each(property, (v,k) => this.property[k] = v);

                        // Clear out any state errors
                        this.clearErrors();
                        this.scrollToTop();

                        this.saveToStorage();

                        return true
                    })
            },
            async validateStep4() {

                if ( !this.applicant.watersense) {
                    return this.showError('Please tell us why getting a WaterSense toilet matters.')
                }

                var data = {
                    applicant: this.applicant,
                    reference   : this.reference,
                };

                return axios.post(this.domain + ENDPOINTS.step4, qs.stringify(data))
                    .then( response => {

                        // var {application,property} = response.data;

                        // Merge the data into our classes
                        // application     && _each(application, (v,k) => this.application[k] = v);
                        // property        && _each(property, (v,k) => this.property[k] = v);

                        // Clear out any state errors
                        this.clearErrors();
                        this.scrollToTop();

                        this.saveToStorage();

                        return true;
                    })
            },
            async validateStep5() {

                if ( !this.agreed ) {
                    this.states.agreed = false;
                    return Promise.reject('You must check the box for the agreement before submitting your application');
                }

                var data = {
                    rebate_id      : _get(this.partner, 'rebate.id'),
                    applicant      : this.applicant,
                    utility_account: this.utility_account,
                    property       : this.property,
                    owner          : this.owner,
                    application    : this.application,
                    reference      : this.reference
                };

                if ( this.admin ) {
                    data.application.submission_type = this.submission_type;
                }

                return axios.post(this.domain + ENDPOINTS.submit, qs.stringify(data))
                    .then( response => {
                        var {created,on_waitlist,rebate_code} = response.data;

                        this.waitlist = on_waitlist;
                        this.rebate_code = rebate_code;

                        if ( !created ) {
                            this.apiError('Sorry, there was an unknown error and your application was not submitted.');
                            return Promise.reject();
                        }

                        if ( !rebate_code ) {
                            this.noRebateCode();
                        }

                        this.saveToStorage();

                        return true
                    })
            },

            completed() {
                this.finished = true;
                this.$nextTick( () => this.$refs.finishedModal.show() );
                this.$emit('completed')
                this.clearStorage();
            },

            handleError(error ) {

                DEBUG && console.error('Error:', error);

                if ( !error ) {
                    return;
                }

                Toasted.catchError(error);

                var response = (error||{}).response||{};

                var {errors,message} = response.data || {};

                errors && Object.keys(errors).forEach( k => {
                    this.states[k] = false;
                });

                if ( response && response.status === 500 ) {
                    this.apiError();
                }

            },

            apiError( message ) {

                DEBUG && console.error('API Error:', message);

                this.apiErrorMessage = message;
                this.showApiErrorAlert = true;
            },

            noRebateCode() {
                this.showNoRebateCodeAlert = true;
            },

            scrollToTop() {
                try {
                    this.$refs.wizard.$el.scrollIntoView();
                } catch(Err) {
                    // nada, rien
                }
            },

            restart() {
                this.clearStorage();
                this.loaded          = false;
                this.loading         = false;
                this.agreed          = false;
                this.finished        = false;
                this.partner_id      = null;
                this.applicant       = new Applicant();
                this.property        = new Property();
                this.owner           = new Owner();
                this.application     = new Application();
                this.reference       = new Reference();
                this.utility_account = new UtilityAccount();
                this.partner         = new Partner();
                this.states          = this.emptyStates();
                this.waitlist        = null;
                this.rebate_code     = null;
                this.$refs.wizard.reset();
            },

            saveToStorage()
            {
                if (hasLocalStorage()) {

                    localStorage.setItem('rebate_application', JSON.stringify({...this.$data}))

                }
            },
            loadFromStorage()
            {
                if (hasLocalStorage()) {

                    let json = localStorage.getItem('rebate_application');
                    if (json) {
                        try {
                            let data = JSON.parse(json);
                            Object.keys(data).forEach( key => {
                                this[key] = data[key];
                            });
                            console.log('Data pulled from local storage');
                        } catch (err) {
                            console.warn(err,json);
                        }
                    }
                }
            },
            clearStorage()
            {
                if (hasLocalStorage()) {
                    localStorage.removeItem('rebate_application')
                }
            },
            showError(msg)
            {
                try{
                    this.$bvToast.toast(msg, {variant:'danger'});
                } catch(err) {
                    this.apiError(msg)
                }
            }
        },


        watch: {
            'applicant.email_opt_in': {
                immediate: true,
                handler(val) {
                    if (val === '0') {
                        this.applicant.email_opt_in = 0;
                    }
                }
            }
        }
    };

</script>