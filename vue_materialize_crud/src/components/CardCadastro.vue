<template>
  <div :class="classBase">
    <div :class="classBase2">
      <md-card>
        <md-card-header class="md-card-header-text md-card-header-green">
          <div class="card-text">
            <slot name="header"></slot>
          </div>
          <slot name="header_button"></slot>
        </md-card-header>

        <md-card-content>
          <slot name="body"></slot>
        </md-card-content>
      </md-card>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    size: {
      type: String,
      default() {
        return "100";
      }
    },

    classBase: {
      type: String,
      default: "md-layout"
    },

    classBase2: {
      type: String,
      default() {
        return "md-layout-item md-size-" + this.size;
      }
    }
  },
  methods: {}
};
</script>