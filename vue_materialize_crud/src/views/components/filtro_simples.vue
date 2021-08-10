    <template>
  <div class="md-layout">
    <div class="md-layout-item md-size-80">
      <md-field>
        <md-input
          name="searchQuery"
          type="text"
          :placeholder="placeholder"
          v-model="filtroValue"
          @keyup.enter="enter"
          v-on:change="change_value"
        ></md-input>
      </md-field>
    </div>

    <div class="md-layout-item md-size-20">
      <md-button
        title="Pesquisar"
        class="md-round md-just-icon md-info"
        v-on:click="load_data"
        :disabled="disabled"
        type="button"
      >
        <md-icon>search</md-icon>
      </md-button>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    placeholder: {
      type: String,
      default: function() {
        return "Filtrar por nome";
      }
    },
    value: {
      type: String,
      default: function() {
        return "";
      }
    },
    disabled: {
      type: Boolean,
      default: function() {
        return true;
      }
    },
    onSearch: {
      type: Function,
      default: function() {
        return null;
      }
    }
  },
  computed: {
    filtroValue: {
      get: function() {
        return this.value;
      },
      set: function(value) {
        this.$emit("input", value);
      }
    }
  },
  model: {
    prop: "value",
    event: "input"
  },
  data() {
    return {
      //tems_clientes: []
    };
  },
  methods: {
    load_data() {
      if (this.onSearch != null) {
        this.onSearch();
      }
    },
    enter() {
      this.load_data();
    },
    change_value() {
      this.$emit("input", this.value);
    }
  }
};
</script>              