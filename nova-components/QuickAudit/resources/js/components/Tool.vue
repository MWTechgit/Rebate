<template>
    <div class="audit-comp">
        <div v-if="apiError" class="py-4 border-b border-40">
            <p>The following error was received: <span style="color:red">{{ apiError }}</span></p>
            <button class="mt-3 btn btn-default bg-grey-light hover:bg-grey text-grey-darkest" type="button" @click="reload">
                Try again?
            </button> 
        </div>
        <div v-if="fetched">
            <div v-if="(audit.errors||[]).length || errorCount">
                <p>The following errors were received when trying to audit this application. It may be corrupted.</p>
                <div v-for="(err,i) in audit.errors" class="my-2" style="color:red">{{ err }}</div>
                <div v-for="(err,k) in errors" class="my-2" style="color:red">{{ err }}</div>
            </div>
            <div v-if="audit.passed">
                <p>Passed successfully.  No full name, email address, property address or utility account number matches found.</p>
            </div>
            <div v-else>
                <div v-for="item in audit.items">
                    <p class="mb-1 audit-header" v-html="item.text"></p>
                    <div v-if="showItem[item.match]">
                        
                        <div v-if="loadingItems">
                            <div class="my-2">Loading items...</div>
                        </div>

                        <div v-else-if="errors[item.match]">
                            <div class="my-2" style="color:red">{{ errors[item.match] }}</div>
                        </div>

                        <div v-else-if="auditItems[item.match].length < 1">
                            <div class="my-2" style="color:red">Loading for {{ item.match }} items failed</div>
                        </div>

                        <table v-else class="w-full text-left table-audit mt-2">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>App #</th>
                                    <th>Status</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="row in auditItems[item.match]">
                                    <td>
                                        <div>{{ row.full_name }}</div>
                                        <div class="audit-date text-80">Submitted {{ row.submitted }}</div>
                                    </td>
                                    <td v-if="row.wait_listed">
                                        <router-link :to="{
                                            name: 'detail',
                                            params: {
                                                resourceName: 'wait-listed-applications',
                                                resourceId: row.application_id
                                            }
                                        }" class="no-underline dim text-primary">
                                            {{ row.rebate_code }}
                                        </router-link>
                                    </td>
                                    <td v-else-if="false == row.archived_application">
                                        <router-link :to="{
                                            name: 'detail',
                                            params: {
                                                resourceName: 'applications',
                                                resourceId: row.application_id
                                            }
                                        }" class="no-underline dim text-primary">
                                            {{ row.rebate_code }}
                                        </router-link>
                                    </td>
                                    <td v-else >
                                        <a class="no-underline dim text-primary" href="#">{{ row.rebate_code }}</a>
                                    <td>
                                        <span class="text-80" style="font-size: 14px;">{{ row.status }}</span>
                                    </td>
                                    <td>
                                        <div style="font-size: 14px;">{{ row.address }}</div>
                                        <div class="audit-partner text-80">{{ row.partner }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <a @click="toggleMore(item)" class="text-sm no-underline dim text-primary" href="javascript:void(0)" v-html="showItem[item.match] ? 'Hide Details' : 'Show Details'"></a>
                </div> <!-- item -->
            </div>
        </div>
    </div>
</template>

<script>



// var testing2 = {"passed":true,"items":[]};


export default {
    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            audit: {},
            fetched: false,
            showItem: {
                address: false,
                utility: false,
                name: false,
                email: false,
            },
            auditItems: {
                address: [],
                utility: [],
                name: [],
                email: [],
            },

            loadingAudit: false,
            loadingItems: false,

            apiError: false,

            errors: {}
        }
    },

    computed: {
        errorCount() {
            return Object.keys(this.errors||{}).length;
        }
    },

    mounted() {
        this.reload();
    },

    methods: {

        reload() {
            this.apiError = false;
            this.loadAudit();
            this.loadItems();
        },

        async loadAudit() {
            this.loadingAudit = true;
            axios.get(`/nova-vendor/quick-audit/applications/${this.resourceId}`)
            .then(response => {
                this.audit = response.data; //testing2; //
                console.log('Audit result:', this.audit);
                this.fetched = true;
            })
            .catch(err => this.fail(err))
            .finally(() => this.loadingAudit = false )
        },

        async loadItems() {
            this.loadingItems = true;
            axios.get(`/nova-vendor/quick-audit/applications/${this.resourceId}/items`)
            .then(response => {

                // response.data = testing;
                // console.log('Response:', response.data);
                
                this.auditItems = _.mapValues( response.data, (obj, key) => {
                    if ( obj.error ) {
                        this.errors[key] = obj.error;
                        return [];
                    } else {
                        return obj;
                    }
                })

                this.$nextTick( () => {
                    console.log( this.errorCount + ' errors found in items:', this.errors)
                });
            })
            .catch(err => this.fail(err))
            .finally(() => this.loadingItems = false )
        },

        toggleMore(item) {
            this.showItem[item.match] = !this.showItem[item.match];
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
