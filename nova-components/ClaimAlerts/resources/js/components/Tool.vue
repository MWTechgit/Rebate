<template>
    <div>
    	<div v-if="dataFetched">
    		<div class="alert alert-warning mt-4" v-if="claim.denied">
                <h4 class="alert-heading">Claim Denied</h4>
                <p>This claim was denied <span :title="claim.denied_on">{{ claim.denied_diff }}</span> by {{ claim.denied_by }}</p>
                <hr>
                <p v-if="claim.reason">{{ claim.reason }}</p>
            </div>
            <div class="alert alert-success mt-4" v-else-if="claim.approved">
                <h4 class="alert-heading">Claim Approved</h4>
                <p>This claim was approved <span :title="claim.approved_on">{{ claim.approved_diff }}</span> by {{ claim.approved_by }}</p>
                <p v-if="claim.reason">{{ claim.reason }}</p>
            </div>
            <div class="alert alert-info mt-4" v-if="claim.expiring_soon && !claim.awarded">
                <h4 class="alert-heading">Expiring Soon</h4>
                <p>This claim expires <span :title="claim.expires_on">{{ claim.expires_diff }}</span></p>
            </div>
            <div class="alert alert-danger mt-4" v-if="claim.expired">
                <h4 class="alert-heading">Expired</h4>
                <p>This claim expired <span :title="claim.expired_on">{{ claim.expired_diff }}</span></p>
            </div>
    	</div>
    </div>
</template>

<script>
export default {
    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            claim      : null,
            dataFetched: false,
            apiError   : null
        }
    },

    mounted() {
        this.retry();  
    },

    methods: {

        retry() {
            this.apiError = null;
            axios.get(`/api/claims/${this.resourceId}`)
            .then(response => {
                var data = response.data;
                this.claim = data.data || data;// In case the resource is wrapped.
                this.dataFetched = true;
            })
            .catch(err => this.fail(err))
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
