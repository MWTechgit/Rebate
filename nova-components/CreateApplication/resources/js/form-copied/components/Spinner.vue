<!--

This component is to show a spinner

Arguments:
    v-bind:busy         - (bool) When to show the spinner
    v-bind:scale        - (Number)
    :label              - (String)

Events:
    NONE

-->
<template>
    <component :is="element" v-if="busy" class="browards-loader"></component>
</template>

<script>

    export default {

        name: 'Spinner',

        props: {
            busy: {
                type: Boolean,
                required: true,
                default: false,
            },
            scale: {
                type: [Number,String],
                required: false,
                default: 2,
            },
            label: {
                type: String,
                required: false,
                default: () => 'Loading'
            },
            inline: {
                type: Boolean,
                required: false,
                default: false
            }
        },
        computed: {
            element() {
                return this.inline ? 'span' : 'div';
            }
        }
    };
</script>
