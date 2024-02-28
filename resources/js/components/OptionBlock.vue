<template>
  <div class="featured-post-options option-block">
    <div class="option-block-header">
      <h3 class="option-block-title">{{blockTitle}}</h3>
      <div v-if="metaKey" class="option-toggle">
        <input 
        type="checkbox" 
        :id="metaKey"
        @change="$emit('updateConfig', metaKey, $event.target.checked)"
        v-model="active"
        >
        <label :for="metaKey"></label>
      </div>
    </div>
    <div class="option-block-inner-wrapper" :class="{faded : (!config[metaKey] && fade)}">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  // props: ['blockTitle', 'config', 'metaKey'],
  props: {
    blockTitle: {},
    metaKey: {},
    config: {},
    fade: {
      default: true,
    }
  },
  data() {
    return {
      active: this.config[this.metaKey],
    }
  },
  watch: {
    config: function(newVal, oldVal) {
      this.active = newVal[this.metaKey];
    }
  }
}
</script>

<style>

</style>