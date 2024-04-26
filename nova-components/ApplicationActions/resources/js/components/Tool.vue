<template>
    <div>
        <div class="application-actions pt-3">
            <div v-if="apiError" class="py-4 border-b border-40">
                <p>The following error was received: <span style="color:red">{{ apiError }}</span></p>
                <button class="mt-3 btn btn-default bg-grey-light hover:bg-grey text-grey-darkest" type="button" @click="reload">
                    Try again?
                </button> 
            </div>
            <div class="buttons mb-2">
                <div class="flex flex-row">
                    <div v-show="application && showActionForm">
                        <button @click="toggleApproveForm()" class="btn btn-default btn-success">
                            {{ approveForm.show ? 'Cancel' : 'Approve Application' }}
                        </button>
                        <button @click="toggleDenyForm()" class="btn btn-default btn-primary">
                            {{ denyForm.show ? 'Cancel' : 'Deny Application' }}
                        </button>
                    </div>
                    <router-link v-if="claimId" :to="{
                        name: 'detail',
                        params: {
                            resourceName: 'claims',
                            resourceId: claimId
                        }
                    }" class="ml-1 btn btn-default bg-grey-light hover:bg-grey text-grey-darkest">
                        View Claim
                    </router-link>
                    <router-link v-if="nextId" :to="{
                        name: 'detail',
                        params: {
                            resourceName: 'applications',
                            resourceId: nextId
                        }
                    }" class="ml-1 btn btn-default bg-grey-light hover:bg-grey text-grey-darkest">
                        Next Application
                    </router-link>
                </div> <!-- .flex -->
            </div>

            <div v-show="approveForm.show" class="border-t border-40 mt-6">
                <div class="flex border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="denial-message">Confirm</label>
                    </div>
                    <div class="py-6 px-8 w-4/5">
                        <button @click="approveApplicationAndLens()" class="btn btn-default btn-success inline-flex items-center relative">
                            Finalize Approval
                        </button>

                        <button @click="approveApplicationAndNext()" v-if="nextId" class="btn btn-default bg-grey-light hover:bg-grey text-grey-darkest">
                            Finalize and Next
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
                            <option v-for="reason in denyForm.fields.reasons" v-bind:value="reason" v-bind:key="reason.id">{{ reason.reason }}</option>
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
                        <button @click="denyApplicationAndLens()" :disabled="!denyFormValidated" class="btn btn-default btn-danger inline-flex items-center relative">
                            Finalize Denial
                        </button>

                        <button @click="denyApplicationAndNext()" v-if="nextId" :disabled="!denyFormValidated" class="btn btn-default bg-grey-light hover:bg-grey text-grey-darkest">
                            Finalize and Next
                        </button>
                    </div>
                </div> <!-- denial reason textarea -->
            </div> <!-- denial-form -->
        </div> <!-- .application-actions -->
    </div>
</template>

<script>
    export default {
        props: ['resourceName', 'resourceId', 'field'],

        data() {
            return {
                application: null,

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
                },

                nextId: null
            };
        },

        computed: {
            showActionForm() {
                return false === this.application.approved && false === this.application.denied;
            },
            denyFormValidated() {
                return this.denyForm.fields.reason.message != '';
            },
            lens() {
                return this.field.cameFromLens || 'application-inbox';
            },
            claimId() {
                return (( this.application||{}).claim || {}).id;
            }
        },

        mounted() {
            this.reload();
        },

        methods: {

            reload() {

                this.apiError = null;

                axios.get('/denial-reasons', {params: {type: 'application'}})
                .then(response => {
                    this.denyForm.fields.reasons = response.data;
                })
                .catch(err => this.fail(err))

                axios.get(`/api/applications/${this.resourceId}`)
                .then(response => {
                    this.application = response.data.data;
                })
                .catch(err => this.fail(err))

                axios.get('/api/applications/' + this.resourceId + '/next/' + this.lens )
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

            approveApplicationAndLens() {
                this.approveApplication()
                .then(response => {
                    this.$router.push({
                        name: 'lens',
                        params: {resourceName: this.resourceName, lens: this.lens}
                    }, () => {
                        this.$toasted.show(response.data.message, { type: 'success', position: 'top-right'});
                    });
                })
                .catch( err => {
                    // already caught
                })
            },

            approveApplicationAndNext() {
                this.approveApplication()
                .then(response => {
                    this.$router.push({
                        name: 'detail',
                        params: {resourceName: 'applications', resourceId: this.nextId}
                    }, () => {
                        this.$toasted.show(response.data.message, { type: 'success', position: 'top-right'});
                    });
                })
                .catch( err => {
                    // already caught
                })
            },

            async approveApplication() {
                return axios.post('/applications/' + this.resourceId + '/approve')
                .catch(err => this.fail(err));
            },

            denyApplicationAndLens() {
                this.denyApplication()
                .then(response => {
                    this.$router.push({
                        name: 'lens',
                        params: {resourceName: this.resourceName, lens: this.lens}
                    }, () => {
                        this.$toasted.show(response.data.message, { type: 'success', position: 'top-right'});
                    });
                })
                .catch( err => {
                    // already caught
                })
            },

            denyApplicationAndNext() {
                this.denyApplication()
                .then(response => {
                    this.$router.push({
                        name: 'detail',
                        params: {resourceName: 'applications', resourceId: this.nextId}
                    }, () => {
                        this.$toasted.show(response.data.message, { type: 'success', position: 'top-right'});
                    });
                })
                .catch( err => {
                    // already caught
                })
            },

            async denyApplication() {
                return axios.post('/applications/' + this.resourceId + '/deny', {
                    reason: this.denyForm.fields.reason.message
                }).catch(err => this.fail(err));
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
    };
</script>
