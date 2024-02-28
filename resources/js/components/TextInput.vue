<template>
  <div class="option-list-text tw-py-2">
    <div class="option-list-text-header flex justify-between items-center">
      <h3 class="option-item-text-title tw-mb-2">{{optionTitle}}</h3>
      <div class="option-text">
        <input 
        type="text" 
        :id="`inner-${metaKey}`"
        @change="$emit('updateConfig', metaKey, $event.target.value)"
        v-model="text"
        >
        <label :for="`inner-${metaKey}`"></label>
      </div>
    </div>
    <slot />
  </div>
</template>

<script>
export default {
  props: {
    optionTitle: {},
    metaKey: {},
    config: {},
  },
  data() {
    return {
      text: this.config[this.metaKey],
    }
  },
  watch: {
    config: function(newVal, oldVal) {
      this.text = newVal[this.metaKey];
    }
  }
}
</script>

<style>

</style>