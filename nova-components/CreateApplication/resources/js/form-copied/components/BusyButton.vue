<!--
A button that shows a spinner when busy

Arguments

    :busy           - (Boolean)
    :busy-text      - (String) Optional.
    :klass          - (String) Optional.

Events:
    @click

-->
<template>
    <button type="button" :class="['btn', {disabled: busy}]" :disabled="busy" @click="click">
        <span v-if="busy"><spinner :busy="busy" :scale=1 v-bind:inline="true"></spinner> {{ busyText }}</span>
        <span v-else ><slot></slot></span>
    </button>
</template>


<script>

import Spinner from "./Spinner.vue";

export default {

    name: 'BusyButton',

    components: { Spinner },
    props: {
        busy: {
            required: true,
        },
        busyText: {
            type: String,
            required: false,
            default: () => 'Loading'
        }

    },

    data () {
        return {

        };
    },
    computed: {
        classObject() {
            return [ 'btn', this.klass, {disabled: this.busy} ];
        }
    },
    methods: {
        click() {
            if ( !this.busy ) {
                this.$emit('click');
            }
        }
    }

};
</script>