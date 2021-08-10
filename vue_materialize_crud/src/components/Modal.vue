<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container" v-click-outside="closeModal" v-bind:style="getStyleWrapper()">
          <div style="display:none" class="modal-header">
            <slot name="header"></slot>
          </div>

          <div class="modal-body text-center">
            <slot name="body"></slot>
          </div>

          <div class="modal-footer">
            <slot name="footer"></slot>
          </div>

          <md-button class="md-just-icon md-round modal-default-button" @click="closeModal">
            <md-icon>close</md-icon>
          </md-button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import("../assets/css/modal.css");

export default {
  props: {
    force_height: Number,
    default: function() {
      return 1;
    }
  },
  methods: {
    closeModal: function() {
      this.$emit("close");
    },
    getStyleWrapper() {
      if (this.force_height == 0) {
        // return "position: absolute; top: 2%; left: 20%; max-height: 90%; max-width: 75%";
        // return "";
      }

      if (this.force_height == 1) {
        //return "position: absolute; top: 2%; left: 20%; max-height: 90%; max-width: 75%";
      }

      return "position: absolute; top: 2%; left: 20%; max-height: 90%; min-width: 70%; max-width: 77%";
    }
  }
};
</script>

<style lang="scss">
/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>
