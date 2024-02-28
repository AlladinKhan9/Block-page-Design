<template>
  <div class="option-list-toggle tw-pb-2">
    <div class="option-list-toggle-header tw-flex tw-justify-between tw-items-center">
      <h3 class="option-item-toggle">{{optionTitle}}</h3>
      <div class="option-toggle">
        <input 
        type="checkbox" 
        :id="`inner-${metaKey}`"
        @change="$emit('updateConfig', metaKey, $event.target.checked)"
        v-model="active"
        >
        <label :for="`inner-${metaKey}`"></label>
      </div>
    </div>
    <slot />
  </div>
</template>

<script>
export default {
  // props: ['optionTitle', 'metaKey', 'config'],
  props: {
    optionTitle: {},
    metaKey: {},
    config: {},
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