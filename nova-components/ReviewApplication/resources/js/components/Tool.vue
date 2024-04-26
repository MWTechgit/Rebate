<template>
    <div>
        <div v-if="apiError" class="py-1 border-b border-40">
            <p>The following error was received: <span style="color:red">{{ apiError }}</span></p>
            <button class="mt-3 btn btn-default bg-grey-light hover:bg-grey text-grey-darkest" type="button" @click="fetch">
                Try again?
            </button>
        </div>

        <div v-if="dataFetched">
            <div class="alert alert-warning mt-4" v-if="missingData">
                <h4 class="alert-heading">Application Corrupted</h4>
                <p>This application is missing necessary data. It may cause problems on pages where it is displayed.</p>
            </div>
            <div class="alert alert-warning mt-4" v-if="application.denied">
                <h4 class="alert-heading">Application Denied</h4>
                <p>This application was denied <span :title="application.denied_on">{{ application.denied_diff }}</span> by {{ application.denied_by }}</p>
                <hr>
                <p v-if="application.reason">{{ application.reason }}</p>
            </div>
            <div class="alert alert-success mt-4" v-else-if="application.approved">
                <h4 class="alert-heading">Application Approved</h4>
                <p>This application was approved <span :title="application.approved_on">{{ application.approved_diff }}</span> by {{ application.approved_by }}</p>
                <p v-if="application.reason">{{ application.reason }}</p>
            </div>
        </div>

        <div class="flex" v-if="dataFetched">
            <!-- APPLICATION DETAILS -->
            <div class="flex-1 mr-6">
                <div>
                    <div class="flex border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">Applicant</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <router-link :to="{
                                name: 'detail',
                                params: {
                                    resourceName: 'applicants',
                                    resourceId: applicant.id
                                }
                            }" class="no-underline font-bold dim text-primary">
                                {{ applicant.full_name }}
                            </router-link>
                            <p class="my-1 text-90">{{ application.rebate_code }} <span class="label">{{ application.status }}</span></p>

                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">Phone</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90">{{ applicant.phone }}</p>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">E-Mail</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90"><a :href="'mailto:' + applicant.email">{{ applicant.email }}</a></p>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="mb-2 font-normal text-80">Partner</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90 mb-2">{{ rebate.partner.name }}, {{ rebate.fy_year }}</p>
                            <div class="label">Applied For: {{ application.rebate_count }}</div>
                            <div class="label">Remaining: {{ rebate.remaining }}</div>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="mb-2 font-normal text-80">Property</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90 mb-2">
                                {{ property.address.full }}
                            </p>
                        </div>
                    </div>

                    <!-- Year Built -->
                    <div class="flex border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">Structure</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90">
                                {{ property.building_type }}
                                <span class="label">Built {{ property.year_built || '?' }}</span>
                                <span class="label">Bathrooms: {{ property.bathrooms }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Years Lived -->
                    <div class="flex border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">Years Lived</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90">{{ property.years_lived }}</p>
                        </div>
                    </div>

                    <!-- Occupants -->
                    <div class="flex border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">Occupants</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90">{{ property.occupants }}</p>
                        </div>
                    </div>

                    <!-- Property Owner -->
                    <div class="flex border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">Property Owner</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p v-if="property.owner" class="text-90">
                                <router-link :to="{
                                    name: 'detail',
                                    params: {
                                        resourceName: 'owners',
                                        resourceId: property.owner.id
                                    }
                                }" class="no-underline font-bold dim text-primary">
                                    {{ property.owner.full_name }}
                                </router-link>
                            </p>
                            <p v-else class="text-90">
                                Applicant
                            </p>
                        </div>
                    </div>

                    <div class="flex py-1 border-b border-40" >
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">Bathrooms</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90" v-if="property.is_residential">
                            {{ property.half_bathrooms || '0'}} Half, {{ property.full_bathrooms || '0'}} Full, {{ property.toilets || '0' }} Toilets
                            </p>
                            <p class="text-90" v-else>
                            {{ property.bathrooms || '0'}} Bathrooms, {{ property.toilets || '0' }} Toilets
                            </p>
                        </div>
                    </div>

                    <div class="flex py-1 border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">Original Toilet?</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90">{{ property.original_toilet }}</p>
                        </div>
                    </div>

                    <div class="flex py-1 border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">Gallons Per Flush</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90">{{ property.gallons_per_flush }}</p>
                        </div>
                    </div>

                    <div class="flex py-1 border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">Submitted by</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90">{{ application.submission_type }}</p>
                        </div>
                    </div>

                    <div class="flex py-1 border-b border-40">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">Reference</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90" v-if="application.reference">{{ application.reference.type }}
                                <small v-if="application.reference.info_response">{{ application.reference.info_response }}</small>
                            </p>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">Links</h4>
                        </div>
                        <div class="w-2/3 py-1">

                            <!-- EDIT PROPERTY DETAILS -->
                            <router-link :to="{
                                name: 'edit',
                                params: {
                                    resourceName: 'properties',
                                    resourceId: property.id
                                }
                            }" class="no-underline dim text-primary">
                                Edit Property Details
                            </router-link>
                            &nbsp;|&nbsp;

                            <!-- EDIT PROPERTY ADDRESS -->
                            <router-link :to="{
                                name: 'edit',
                                params: {
                                    resourceName: 'addresses',
                                    resourceId: property.address.id
                                }
                            }" class="no-underline dim text-primary">
                                Edit Property Address
                            </router-link>
                        </div>
                    </div>

                    <div class="flex py-1 border-b border-40" v-if="application.watersense">
                        <div class="w-1/3 py-1">
                            <h4 class="font-normal text-80">WaterSense Reason</h4>
                        </div>
                        <div class="w-2/3 py-1">
                            <p class="text-90">{{ application.watersense }}</p>
                        </div>
                    </div>


                </div>
            </div> <!-- .flex-1 -->

            <!-- GMAP EMBED -->
            <div class="flex-1">
                <div id="gmap-embed-wrap">
                    <div id="gmap-embed">
                        <img v-bind:src="mapSrc" />
                    </div>
                </div>
                <div class="py-1 border-b border-40">
                    <h2 class="mb-3 font-normal text-90">Water Utility Information</h2>

                    <a v-if="utilityAccount.id" class="no-underline dim text-primary"
                            :href="`/admin/resources/utility-accounts/${utilityAccount.id}`"
                        >(Manage)</a>

                    <!-- no utility account was saved for this application -->
                    <div v-if="!utilityAccount.id && property.id">
                        No Record Found
                        <a class="no-underline dim text-primary"
                            :href="`/admin/resources/utility-accounts/new?viaResource=properties&viaResourceId=${property.id}&viaRelationship=utilityaccount`"
                        >(Create)</a>
                    </div>

                    <div v-else-if="utilityAccount.id">

                        <div class="py-1 border-b border-40">

                            <h4 class="mb-2 font-normal text-80">Billing Address</h4>

                            <p class="text-90" v-if="utilityAccount.address">
                                {{ utilityAccount.address.full }}
                                <router-link :to="{
                                    name: 'edit',
                                    params: {
                                        resourceName: 'addresses',
                                        resourceId: utilityAccount.address.id
                                    }
                                }" class="no-underline dim text-primary">
                                    (Edit)
                                </router-link>
                            </p>

                            <p class="text-90" v-else>
                                <!-- no address was saved for the utility account. Allow an admin to make one -->
                                No Record Found
                                <a class="no-underline dim text-primary"
                                :href="`/admin/resources/addresses/new?viaResource=utility-accounts&viaResourceId=${utilityAccount.id}&viaRelationship=address`"
                            >(Create)</a>

                            </p>
                        </div>

                        <div class="py-1 border-b border-40">

                            <h4 class="mb-2 font-normal text-80">Account Number</h4>
                            <p class="text-90" >
                                {{ utilityAccount.account_number}}
                                <router-link :to="{
                                    name: 'edit',
                                    params: {
                                        resourceName: 'utility-accounts',
                                        resourceId: utilityAccount.id
                                    }
                                }" class="no-underline dim text-primary">
                                    (Edit)
                                </router-link>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="py-1">
                    <h2 class="mb-3 font-normal text-90">Remittance Address Information</h2>
                    <div v-if="application.has_remittance_address">
                        <h4 class="mb-2 font-normal text-80">
                            Rebate Mail To Address
                        </h4>
                        <p class="text-90">
                            <p class="mb-2">
                                {{ application.mail_to_address }}
                                <router-link :to="{
                                    name: 'edit',
                                    params: {
                                        resourceName: 'remittance-addresses',
                                        resourceId: application.address.id
                                    }
                                }" class="no-underline dim text-primary">
                                    (Edit)
                                </router-link>
                            </p>
                        </p>
                    </div>
                    <div v-else>
                        Same as property address

                        <a class="no-underline dim text-primary"
                            :href="`/admin/resources/remittance-addresses/new?viaResource=applications&viaResourceId=${application.id}&viaRelationship=address`"
                        >(Edit)</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import initGmaps from '../property-map.js';
export default {
    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            busy       : false,
            application: null,
            applicant  : null,
            property   : null,
            applicant  : null,
            rebate     : null,
            utility    : null,
            // Don't let it render until we have the data
            dataFetched: false,
            apiError   : null,
        }
    },

    computed: {
        missingData() {
            return !(this.application||{}).id
                || !(this.applicant||{}).id
                || !(this.property||{}).id
                || !(this.property.address||{}).id;
        },
        mapSrc() {
            var mapUrl = 'https://maps.googleapis.com/maps/api/staticmap?center=plantation,fl&zoom=11&size=400x500&key=';
            return mapUrl + Nova.config.gmap_api_key;
        }
    },

    mounted() {
        this.fetch();
    },

    methods: {

        fetch() {
            this.apiError = false;
            this.busy = true;
            axios.get(`/api/applications/${this.resourceId}`)
            .then(response => {
                var application     = response.data.data;

                this.application    = application;
                this.property       = application.property||{address:{}};
                this.applicant      = application.applicant||{};
                this.rebate         = application.rebate||{};
                this.utilityAccount = application.utilityAccount||{address:{}};

                this.property.address.full && initGmaps(this.property.address.full);

                // Let vue render (avoid null property errors)
                this.dataFetched = true;
            })
            .catch(err => this.fail(err) )
            .finally(() => this.busy = false );
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
