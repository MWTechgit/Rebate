<template>

    <div>

        <div v-if="waitlist">

           <p>
                {{ name }}, thank you for completing the rebate application. Unfortunately, there are no rebates available at the moment, but we
                have added you to our waiting list and will notify you when one becomes available in your area.
            </p>
            <p>
                Below you will find your confirmation code. Please save it for your records. If you need to
                contact us about your status on the waiting list, please provide us
                with your confirmation code so that we can easily retrieve your records.
            </p>
            <p>
                <b>Confirmation code: {{ rebateCode }}</b><br />
                <b>Email address: {{ email }}</b>
            </p>

        </div>

        <div v-else>
        
            <p>
                {{ name }},  thank you for completing the rebate application. We have received your request and it's pending review.
                Once your application has been reviewed we will notify you whether it's been approved or denied.
            </p>

            <p>
                Please allow up to five (5) business days to receive a email confirmation of "Approval or Denial" from the Rebate Administrator regarding your application.
                If approved, you will receive an email with all of the instructions on how to receive your rebate. <span style="text-decoration:underline;">Please read that email carefully</span>.
            </p>
            <p>
                Below you will find your Application Number. Please save it for your records. If you need to contact us about your rebate application,
                please provide us with your Application Number so that we can easily retrieve your records.
                You will also be able to use your Application Number to access our online Rebate Center to check on the status of your application.
            </p>
            <p>
                <b>Application Number: {{ rebateCode }}</b><br />
                <b>Email address: {{ email }}</b>
            </p>

        </div>

        <p>
            <em>
            ** Do not purchase your toilet unless you have received your Approval Confirmation Notification and Number. 
            Toilets purchased before you have been approved may not be eligible for a rebate.**
            </em>
        </p>

        <p>
            <span style="color:#7fa500">While you're waiting for your approval, take advantage of free water-saving showerheads and faucet aerators. 
            <a style="color:#0995d0" href="https://conservationpays.com/partners/">See if your community is participating</a>!</span>
        </p>
    </div>

</template>

<script>

    export default {

        name: 'Finished',

        props: {
            waitlist: {
                type: Boolean,
                default: false
            },
            rebateCode: {
                type: String
            },
            email: {
                type: String
            },
            name: {
                type: String
            }
        }
    };

</script>