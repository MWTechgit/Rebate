<template>
    <a v-if="imageUrl" target="_blank" :href="imageUrl">
        <index-file-field v-bind="$props"></index-file-field>
    </a>
    <index-file-field v-bind="$props" v-else></index-file-field>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
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