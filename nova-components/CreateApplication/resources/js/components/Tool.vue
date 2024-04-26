<template>
    <div>
        <heading class="mb-6">Create Application</heading>

        <card class="flex flex-col items-center justify-center" style="min-height: 300px">

            <rebates-application-form
                ref="wizard"
                :domain="$options.domain"
                @completed="showResetButton"
                id="rebates-application-form"
                admin
                ></rebates-application-form>

            <button class="wizard-btn" v-if="completed" @click="reset">Create Another Application?</button>

        </card>
    </div>
</template>

<script>

var domain = window.location.protocol + '//' + window.location.hostname;

import RebatesApplicationForm from '../form-copied/components/RebatesApplicationForm';

export default {

    domain,

    components: {RebatesApplicationForm},

    data() {
        return {
            completed:false
        }
    },

    methods: {
        showResetButton() {
            this.completed = true;
        },
        reset() {
            this.$refs.wizard.restart();
            this.completed = false;
        }
    }
};
</script>