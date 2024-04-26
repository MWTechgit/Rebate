<template>
    <card class="flex flex-col nova-card">
        <div class="px-6 py-4">
            <h3 class="mb-3 text-90 font-bold text-lg text-center">Applications</h3>
            <div class="mb-2 text-blue"><span>30</span> <a class="text-blue" href="#">New</a></div>
            <div class="mb-2"><span>15</span> <a class="text-blue" href="#">Pending review</a></div>
        </div>
    </card>
</template>

<script>
export default {
    props: [
        'card',

        // The following props are only available on resource detail cards...
        // 'resource',
        // 'resourceId',
        // 'resourceName',
    ],

    created() {
        console.log('card created');
    }
}
</script>
