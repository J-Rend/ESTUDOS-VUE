<template>
  <select v-model="value" v-bind:name="name" v-bind:id="name">
    <option v-for="item in options" :key="item.value" :value="item.value">{{item.label}}</option>
  </select>
</template>

<script >
import $ from "jquery";

export default {
  props: {
    /**
     * Contains the currently selected value. Very similar to a
     * `value` attribute on an <input>. You can listen for changes
     * using 'change' event using v-on
     * @type {Object||String||null}
     */
    value: {},

    name: {
      type: String,
      default() {
        return "id_select";
      }
    },

    options: {
      type: Array,
      default() {
        return [];
      }
    }
  },
  model: {
    prop: "value",
    event: "selected"
  },
  mounted() {
    $("#" + this.name).select2();
  },
  methods: {
    updateValue(value) {
      if (this.isTrackingValues) {
        // Vue select has to manage value
        this.$data._value = value;
      }
      var label_selected = "";

      if (value !== null) {
        if (Array.isArray(value)) {
          value = value.map(val => this.reduce(val));
        } else {
          label_selected = value.label;
          value = this.reduce(value);
        }
      }

      this.$emit("input", value);
      this.$emit("update:label_selected", label_selected);
    }
  }
};
</script>