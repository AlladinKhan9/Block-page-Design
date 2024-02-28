<template>
  <div class="option-list-number">
    <div class="option-list-number-header tw-flex tw-justify-between tw-items-center">
      <h3 class="option-item-number-title">{{optionTitle}}</h3>
      <div class="option-number">
        <input 
        type="number"
        :id="`inner-${metaKey}`"
        @change="$emit('updateConfig', metaKey, $event.target.value)"
        v-model="amount"
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
      amount: this.config[this.metaKey],
    }
  },
  watch: {
    config: function(newVal, oldVal) {
      this.amount = newVal[this.metaKey];
    }
  }
}
</script>

<style>

</style>