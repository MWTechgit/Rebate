<template>
    <form-file-field ref="field" v-bind="$props" />
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.$refs.field.setInitialValue();
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            this.$refs.field.fill(formData)
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.$refs.field.handleChange(value)
        },
    },
}
</script>