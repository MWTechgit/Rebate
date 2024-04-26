<template>
    <a v-if="imageUrl" target="_blank" :href="imageUrl">
        <detail-file-field v-bind="$props"></detail-file-field>
    </a>
    <detail-file-field v-bind="$props" v-else></detail-file-field>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
computed: {
    imageUrl() {
      if (this.field.previewUrl && !this.field.thumbnailUrl) {
        return this.field.previewUrl
      }

      return this.field.thumbnailUrl
    },
  },
}
</script>