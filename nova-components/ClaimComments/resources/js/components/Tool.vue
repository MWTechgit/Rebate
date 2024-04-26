<template>
        <Comments :endpoint="endpoint"></Comments>
</template>

<script>

import Comments from './../../../../../resources/js/components/Comments';

export default {
    props: ['resourceName', 'resourceId', 'field'],

    components: {Comments},

    computed: {
        endpoint() {
            return `/claims/${this.resourceId}/comments`;
        }
    }
}
</script>
