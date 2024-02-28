<template>
  <div class="image-picker tw-flex tw-flex-no-wrap">
    <div class="selected-image tw-w-1/2">
      <div class="selected-image-inner">
        <img :src="featuredImage ? featuredImage.guid : null">
      </div>
    </div>
    <div class="image-list tw-w-1/2 tw-flex tw-flex-wrap">
      <div class="image-list-item" v-for="(image, i) in images" :key="i">
        <input 
        type="radio" 
        name="image-list-input"
        :value="image.ID"
        @change="$emit('updateConfig','default_grid_image', parseInt($event.target.value));"
        :id="`image-input-${i}`">
        <label :for="`image-input-${i}`">
          <img :src="image.guid">
        </label>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['images', 'config'],
  computed: {
    featuredImage() {
      return this.images.find((img) => +img.ID === +this.config.default_grid_image)
    }
  }
}
</script>

<style>

</style>