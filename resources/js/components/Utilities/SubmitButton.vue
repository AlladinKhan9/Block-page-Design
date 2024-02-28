<template>
  <button :class="classes" @click="onClick" :disabled="disabled" :style="style">
    <span v-if="showIcon" class="spin"><svg class="icon icon-loading"><use xlink:href="#icon-loading"></use></svg></span>
    <template v-else><slot></slot></template>
  </button>
</template>

<script>
  import router from '../../router.js';

  export default {
    props: ['classProp', 'submittingProp'],
    data: function() {
      return {
        classes: this.classProp,
        showIcon: false,
        disabled: false,
        style: {},
        submitting: this.submittingProp
      }
    },
    watch: {
      submittingProp: function( value ) {
        this.submitting = value[0];
        if ( !this.submitting )
          this.refresh();

      }
    },
    methods: {
      onClick() {
        this.classes.push( 'loading' );
        this.submitting = true;
        this.showIcon = true;
        this.disabled = true;
        this.style = {
          width: this.$el.offsetWidth + 'px',
          height: this.$el.offsetHeight + 'px'
        };

        this.$emit( 'click' );
      },
      refresh() {
        this.classes = this.classProp;
        this.showIcon = false;
        this.disabled = false;
        this.style = {};
      }
    }
  }
</script>
