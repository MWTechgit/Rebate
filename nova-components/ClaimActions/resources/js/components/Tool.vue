<template>
    <div>
        <div class="claim-actions pt-3">
            <div v-if="apiError" class="py-4 border-b border-40">
                <p>The following error was received: <span style="color:red">{{ apiError }}</span></p>
                <button class="mt-3 btn btn-default bg-grey-light hover:bg-grey text-grey-darkest" type="button" @click="reload">
                    Try again?
                </button> 
            </div>
            <div class="buttons mb-2">
                <div class="flex flex-row">
                    <div v-show="claim && showClaimActions">
                        <button @click="toggleApproveForm()" class="btn btn-default btn-success">
                            {{ approveForm.show ? 'Cancel' : 'Approve Claim' }}
                        </button>
                        <button @click="toggleDenyForm()" class="btn btn-default btn-primary">
                            {{ denyForm.show ? 'Cancel' : 'Deny Claim' }}
                        </button>
                    </div>
                    <router-link v-if="applicationId" :to="{
                        name: 'detail',
                        params: {
                            resourceName: 'applications',
                            resourceId: applicationId
                        }
                    }" class="ml-1 btn btn-default bg-grey-light hover:bg-grey text-grey-darkest">
                        View Application
                    </router-link>
                    <router-link v-if="nextId" :to="{
                        name: 'detail',
                        params: {
                            resourceName: 'claims',
                            resourceId: nextId
                        }
                    }" class="ml-1 btn btn-default bg-grey-light hover:bg-grey text-grey-darkest">
                        Next Claim
                    </router-link>
                </div>
            </div>

            <div v-show="approveForm.show" class="border-t border-40 mt-6">
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label for="award-amount" class="inline-block text-80 pt-2 leading-tight">
                            Amount to Award *
                        </label>
                    </div>
                    <div class="py-6 px-8 w-4/5">
                        <input id="award-amount" v-model="approveForm.fields.awardAmount" class="form-control form-input form-input-bordered" />
                        <div class="help-text help-text mt-2">The max awardable amount for this applicant is <b><span class="text-success">${{ approveForm.rules.maxValue }}</span></b></div>
                    </div>
                </div>
                <div class="flex border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="denial-message">Confirm</label>
                    </div>
                    <div class="py-6 px-8 w-4/5">
                        <button @click="approveClaim()" :disabled="!approveFormValidated" class="btn btn-default btn-success inline-flex items-center relative">
                            Finalize Approval
                        </button>
                    </div>
                </div> <!-- denial reason textarea -->
            </div> <!-- denial-form -->

            <div v-show="denyForm.show" class="border-t border-40 mt-6">
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight">
                            Denial Reason *
                        </label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <select v-model="denyForm.fields.reason" class="form-control form-select mb-3 w-full">
                            <option value="" selected="selected" disabled="disabled">—</option>
                            <option v-for="reason in denyForm.fields.reasons" v-bind:value="reason">{{ reason.reason }}</option>
                        </select>
                    </div>
                </div> <!-- denial reason select -->
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="denial-message">
                            Message *
                        </label>
                    </div>
                    <div class="py-6 px-8 w-4/5">
                        <textarea id="denial-message" v-model="denyForm.fields.reason.message" rows="5" placeholder="Denial message content here..." class="w-full form-control form-input form-input-bordered py-3 h-auto"></textarea>
                    </div>
                </div> <!-- denial reason textarea -->
                <div class="flex border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="denial-message">Confirm</label>
                    </div>
                    <div class="py-6 px-8 w-4/5">
                        <button @click="denyClaim" :disabled="!denyFormValidated" class="btn btn-default btn-danger inline-flex items-center relative">
                            Finalize Denial
                        </button>
                    </div>
                </div> <!-- denial reason textarea -->
            </div> <!-- denial-form -->
        </div> <!-- .claim-actions -->
    </div>
</template>

<script>
    export default {
        props: ['resourceName', 'resourceId', 'field'],

        data() {
            return {
                claim: null,

                apiError: null,

                denyForm: {
                    show: false,
                    fields: {
                        reasons: [],
                        reason: {message: ''}
                    }
                },

                approveForm: {
                    show: false,
                    rules: {
                        maxValue: null,
                    },
                    fields: {
                        awardAmount: null
                    }
                },

                nextId: null,
            };
        },

        computed: {
            showClaimActions() {
                return this.claim && false === this.claim.approved && false === this.claim.denied;
            },
            approveFormValidated() {
                return this.approveForm.fields.awardAmount != ''
                    && this.approveForm.fields.awardAmount <= this.approveForm.rules.maxValue;
            },

            denyFormValidated() {
                return this.denyForm.fields.reason.message != '';
            },

            applicationId() {
                return (this.claim||{}).application_id;
            }
        },

        mounted() {
            this.reload();
        },

        methods: {

            reload() {

                this.apiError = null;

                axios.get(`/api/claims/${this.resourceId}`).then(response => {
                    this.claim = response.data;
                })
                .catch(err => this.fail(err))

                axios.get('/denial-reasons', {params: {type: 'claim'}}).then(response => {
                    this.denyForm.fields.reasons = response.data;
                })
                .catch(err => this.fail(err))

                axios.get('/claims/' + this.resourceId + '/awardable').then(response => {
                    this.approveForm.rules.maxValue = response.data.awardable;
                })
                .catch(err => this.fail(err))

                axios.get('/api/claims/' + this.resourceId + '/next/' + (this.field.cameFromLens||'') )
                        .then(response => this.nextId = response.data.id )
                        .catch(err => this.fail(err))
            },


            toggleApproveForm() {
                this.denyForm.show = false;
                this.approveForm.show = !this.approveForm.show;
            },

            toggleDenyForm() {
                this.approveForm.show = false;
                this.denyForm.show = !this.denyForm.show;
            },

            approveClaim() {
                axios.post('/claims/' + this.resourceId + '/approve', {
                    awarded: parseFloat(this.approveForm.fields.awardAmount)
                }).then(response => this.redirect())
                .catch(err => this.fail(err))
            },

            denyClaim() {
                axios.post('/claims/' + this.resourceId + '/deny', {
                    reason: this.denyForm.fields.reason.message
                }).then(response => this.redirect())
                .catch(err => this.fail(err))
            },

            redirect() {

                let route = this.field.cameFromLens ? {
                    name: 'lens',
                    params: {resourceName: this.resourceName, lens: this.field.cameFromLens}
                } : {
                    name: 'index',
                    params: {resourceName: this.resourceName}
                };

                this.$router.push(route, () => {
                    this.$toasted.show(response.data.message, { type: 'success', position: 'top-right'});
                });
            },

            fail(err) {
                console.error(err);
                if (typeof err === 'string') {
                    var msg = err;
                } else if ((err||{}).response) {
                    var msg = _.get(err.response, 'data.message') || err.response.statusText;
                } else {
                    var msg = err.message || err.name || 'Something went wrong. Contact a system admin.'
                }
                this.apiError = msg;
                try {
                    this.$toasted.show(msg, {type: 'error'})
                } catch (Err) {
                    this.$toasted.show('Something went wrong. Contact a system admin', {type: 'error'})
                }
                return Promise.reject();
            }
        }
    }
</script>
