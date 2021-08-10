<div id="el"></div>

<!-- using string template here to work around HTML <option> placement restriction -->
<script type="text/x-template" id="demo-template">
  <div>
    <p>Selected: {{ selected }}</p>
    <select2 :options="options" v-model="selected">
      <option disabled value="0">Select one</option>
    </select2>
  </div>
</script>

<script type="text/x-template" id="select2-template">
<select>
  <slot></slot>
</select>

 props: ['options', 'value'],
  template: '#select2-template',
  mounted: function () {
    var vm = this
    $(this.$el)
      // init select2
      .select2({ data: this.options })
      .val(this.value)
      .trigger('change')
      // emit event on change.
      .on('change', function () {
        vm.$emit('input', this.value)
      })
  },
  watch: {
    value: function (value) {
      // update value
      $(this.$el)
      	.val(value)
      	.trigger('change')
    },
    options: function (options) {
      // update options
      $(this.$el).empty().select2({ data: options })
    }
  },
  destroyed: function () {
    $(this.$el).off().select2('destroy')
  }
})


</script>
