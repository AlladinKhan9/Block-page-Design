<template>
  <div class="relative flex-none" v-click-outside="offclick" @keydown.esc="offclick">
    <button :class="[buttonClasses]" @click="beforeToggle" ref="toggleButton">
      <slot name="button-content"></slot>
    </button>
    <div :class="[contentClasses]" v-if="active" ref="toggleContent">
      <slot name="toggle-content"></slot>
    </div>
    <div :class="[showOverlay && active ? 'is-active' : '', 'click-shield' ]" v-if="showOverlay" @click="offclick">
    </div>
  </div>
</template>

<script>
  import router from '../../router.js';
  import EventBus from '../../eventbus.js';

  export default {
    props: {
      isActive: Boolean,
      showOverlay: Boolean,
      buttonClassProp: Array,
      contentClassProp: Array,
      buttonToggleClasses: Array,
      contentToggleClasses: Array,
      toggleClasses: Array,
    },
    data: function() {
      return {
        active: this.isActive ?? false,
        action: 'open',
        buttonClasses: this.buttonClassProp ?? [],
        contentClasses: this.contentClassProp ?? [],
        buttonToggleClassesObj:this.buttonToggleClasses ?? [],
        contentToggleClassesObj: this.contentToggleClasses ?? [],
        toggleClassesObj: this.toggleClasses ?? [],
        instant: false,
      }
    },
    computed: {
      routing() {
        return this.$store.getters.routing;
      },
    },
    watch: {
      routing(routing) {
        if ( routing ) {
          this.instant = true;
          this.close();
        }
      },
    },
    created() {
      EventBus.$on( 'closeToggle', () => {
        this.close();
      } );
    },
    mounted() {
      this.buttonToggleClassesObj = this.buttonToggleClassesObj.concat( this.toggleClassesObj );
      this.contentToggleClassesObj = this.contentToggleClassesObj.concat( this.toggleClassesObj );

      if ( this.active ) {
        this.buttonClasses = this.buttonClasses.concat( this.buttonToggleClassesObj );
        this.contentClasses = this.contentClasses.concat( this.contentToggleClassesObj );
      }
    },
    methods: {
      beforeToggle() {
        this.$emit( 'beforeToggle' );

        if ( !this.active ) {
          this.active = true;
          this.action = 'open';
          document.querySelector( 'body' ).classList.add( 'overflow-hidden' );
        } else {
          this.action = 'close';
          document.querySelector( 'body' ).classList.remove( 'overflow-hidden' );
        }

        this.$nextTick( () => {
          setTimeout( () => {
            this.toggle();
          }, 50 );
        } );
      },
      toggle() {
        if ( this.buttonClasses.some( r => this.buttonToggleClassesObj.includes( r ) ) ) {
          this.buttonClasses = this.buttonClassProp ?? [];
        } else {
          this.buttonClasses = this.buttonClasses.concat( this.buttonToggleClassesObj );
        }

        if ( this.contentClasses.some( r => this.contentToggleClassesObj.includes( r ) ) ) {
          this.contentClasses = this.contentClassProp ?? [];
        } else {
          this.contentClasses = this.contentClasses.concat( this.contentToggleClassesObj );
        }

        if ( this.instant ) {
          this.afterToggle();
        } else {
          this.$nextTick( () => {
            setTimeout( () => {
              this.afterToggle();
            }, 250 );
          } );
        }
      },
      afterToggle() {
        if ( this.action == 'close' ) {
          this.$nextTick( () => {
            this.active = false;
          } );
        }

        this.instant = false;
        this.$emit( 'afterToggle' );
      },
      offclick() {
        if ( !this.instant )
          this.close();
      },
      close() {
        if ( this.active )
          this.beforeToggle();
      },
    }
  }
</script>
