<template>
  <span style="width: 100%">
    <table
      border="0"
      class="autocomplete_table"
      style="border-collapse: none !important"
    >
      <tr>
        <td v-if="false">
          <vue_select
            class="vue-select2"
            name="select2"
            :options="items"
            v-model="codigo"
            :searchable="true"
            language="pt-BR"
            :onChange="seleciona"
            placeholder="Digite para pesquisar"
            inner_style="width: 99%"
          ></vue_select>
        </td>

        <td style="width: 70px" valign="top">
          <input
            v-model="codigo"
            type="text"
            class="form-control"
            autocomplete="off"
            :readonly="readonly_id"
            @keyup.enter="select_id"
            v-on:change="select_id"
            :id="id_code != null && id_code != undefined ? id_code : ''"
          />
        </td>
        <td valign="top">
          <input
            type="text"
            v-model="texto"
            class="form-control"
            autocomplete="off"
            placeholder="Digite para pesquisar.."
            @keyup="change_value"
          />
        </td>
        <td style="width: 110px" v-if="show_add_button && false">
          <md-button class="md-primary" @click.native="add">
            <md-icon>add</md-icon>Adicionar
          </md-button>
        </td>
      </tr>
      <tr v-if="show_carregando || show_search">
        <td colspan="2">
          <div v-if="show_carregando">
            <i>Carregando...</i>
          </div>
          <div v-if="show_search" class="autocomplete_field">
            <ul class="md-list md-theme-default">
              <li
                v-for="(item, index) in items"
                :key="index"
                class="md-list-item md-menu-item md-theme-default"
              >
                <a style="cursor: pointer" v-on:click="seleciona2(item)">
                  <span class="md-list-item-text">{{ item[field_text] }}</span>
                </a>
              </li>
            </ul>
          </div>
        </td>
      </tr>
    </table>
  </span>
</template>
<style>
.autocomplete_field {
  max-height: 200px;
  max-width: 700px;

  overflow-y: scroll;
  overflow-x: scroll;
}
.autocomplete_field ul li:hover {
  background: black;
  color: beige;
}
.autocomplete_field ul li {
  padding-top: 5px;
  padding-bottom: 5px;
}

.autocomplete_table {
  width: 99%;
  border: none;
  border-collapse: none !important;
  margin-top: 0px;
  margin-bottom: 0px;
}
</style>

<script>
import Api from "../../library/Api.js";
import Util from "../../library/Util";
import vue_select from "./vue-select/src/Select.vue";
import Alerta from "../../library/Alerta";
export default {
  props: [
    "field_value",
    "field_text",
    "field_codigo",
    "search_method",
    "readonlyid",
    "exclude_itens",
    "show_add",
    "onSelect",
    "onAdd",
    "id_start_value",
    "identi",
    "id_code",
    "compl",
  ],
  components: {
    vue_select,
  },
  model: {
    prop: "id_start_value",
    event: "selected",
  },
  computed: {
    field_search_code() {
      if (this.field_codigo) return this.field_codigo;

      return this.field_value;
    },
  },
  data() {
    return {
      codigo: null,
      texto: "",
      items: [],
      items_busca: [],
      item_selected: null,
      ids_exclude: [],

      show_search: false,
      show_carregando: false,
      show_add_button: false,
      timeout: null,
      readonly_id: true,
    };
  },

  mounted() {
    var self = this;

    if (this.readonlyid != null && this.readonlyid != undefined) {
      this.readonly_id = this.readonlyid;
    }

    if (this.show_add != null && this.show_add != undefined) {
      this.show_add_button = this.show_add;
    }

    if (
      this.id_start_value != null &&
      this.id_start_value != undefined &&
      self.search_method != null &&
      self.search_method != undefined
    ) {
      Api.Call(self.search_method + "?id=" + this.id_start_value, "GET", {})
        .then(function (response) {
          // console.log("response? " , response );
          if (
            response.data != null &&
            response.data.data != null &&
            response.data.data.length > 0
          ) {
            var item = response.data.data[0];

            self.item_selected = item;
            // console.log("Posso montar? ");
            // console.log(self.item_selected);
            self.show_search = false;
            self.codigo = item[self.field_value];
            if (self.field_codigo) {
              self.codigo = item[self.field_codigo];
            }
            self.texto = item[self.field_text];
          }
        })
        .catch(function (error) {
          console.log("Erro no autocomplete: ");
          console.log(error);
        });
    }

    /* Api.Call(self.search_method, "GET", {})
      .then(function(response) {

        if (response.data.data != null && response.data.data != undefined) {
          self.items =  Util.getListToSelect(response.data.data, "id", "nome"); // response.data.data;
          self.items_busca = response.data.data;
         // console.log("busca? ",  self.items );
        } else {
          self.items = Util.getListToSelect(response.data, "id", "nome");
          self.items_busca = response.data;
        }
      })
      .catch(function(error) {
        console.log("Erro no autocomplete: ");
        console.log(error);
      });
      */
  },
  methods: {
    async select_id(e) {
      if (this.codigo == "") {
        this.texto = "";
        this.show_search = false;
      } else {
        var field = this.field_value;

        if (this.field_codigo) {
          field = this.field_codigo;
        }

        var response = await Api.Call(
          this.search_method + "?" + field + "=" + this.codigo,
          "GET",
          {}
        );

        var arrayRetorno = Array();

        if (
          response.data != null &&
          response.data.data != null &&
          response.data.data.length > 0
        ) {
          arrayRetorno = response.data.data;
        } else {
          if (  response.data != null && Array.isArray(response.data)){
            
          arrayRetorno = response.data;
          }
        }

        if (arrayRetorno.length > 0
        ) {
          var item = arrayRetorno[0];

          this.item_selected = item;
          // console.log("Posso montar? ");
          // console.log(self.item_selected);
          var field = this.field_value;

          if (this.field_codigo) {
            field = this.field_codigo;
          }

          this.show_search = false;
          this.codigo = item[field];

          this.texto = item[this.field_text];
          var id = item[this.field_value];

          this.$emit("selected", id);
          this.onSelect(item, this.identi);
        } else {
          Alerta.show(
            "Atenção",
            "Código " + this.codigo + " não localizado",
            "warning"
          );
          this.texto = "";
          this.show_search = false;
          this.onSelect(null, this.identi);
        }
      }
    },
    change_value(e) {
      //console.log("autocomplete?", e);
      if (e.key == "Tab") {
        return;
      }
      if (e.key == "Escape") {
        this.show_search = false;
        this.show_carregando = false;
        return;
      }
      var self = this;

      if (this.timeout != null) {
        clearTimeout(this.timeout);
      }

      this.timeout = setTimeout(function () {
        if (self.texto.length >= 2) {
          self.show_carregando = true;

          var filtr_exclude = "";

          if (self.exclude_itens != null && self.exclude_itens != undefined) {
            self.ids_exclude = self.exclude_itens.map((a) => a.id);

            filtr_exclude = "&exclude=" + self.ids_exclude.join(",");
          }

          if (self.compl != null && self.compl != undefined) {
            filtr_exclude += self.compl;
          }

          Api.Call(
            self.search_method + "?filtro=" + self.texto + filtr_exclude,
            "GET",
            {}
          ).then(function (response) {
            if (response.data != null && response.data.data != null) {
              self.items = response.data.data;
            } else {
              self.items = response.data;
            }
            self.show_search = true;
            self.show_carregando = false;
          });
        } else {
          self.show_search = false;
          self.show_carregando = false;
        }

        if (self.texto == "") {
          self.codigo = "";
        }
      }, 500);
    },
    buscarbyid(id) {
      for (var i = 0; i < this.items_busca.length; i++) {
        if (this.items_busca[i][this.field_value] == id) {
          return this.items_busca[i];
        }
      }

      return null;
    },
    seleciona2(item) {
      if (item == null || item == undefined) {
        this.codigo = "";
        this.texto = "";
        if (this.onSelect != null && this.onSelect != undefined) {
          this.onSelect(null, this.identi);
        }
        this.$emit("selected", this.codigo);
        return;
      }

      this.texto = item[this.field_text];
      var id = item[this.field_value];

      this.codigo = item[this.field_value];

      if (this.field_codigo) {
        this.codigo = item[this.field_codigo];
      }
      this.item_selected = item;

      this.show_search = false;

      if (this.onSelect != null && this.onSelect != undefined) {
        this.onSelect(item, this.identi);
      }

      this.$emit("selected", id);
    },
    seleciona(item) {
      //console.log("seleciona?");
      //console.log(item);
      if (item == null || item == undefined) {
        this.codigo = "";
        this.texto = "";
        return;
      }

      var p_item = this.buscarbyid(item);

      if (p_item == null) {
        return;
      }

      // this.codigo = item[this.field_value];
      this.texto = p_item[this.field_text];
      this.item_selected = p_item;

      this.show_search = false;

      if (this.onSelect != null && this.onSelect != undefined) {
        this.onSelect(p_item);
      }
    },
    add() {
      if (this.onAdd != null && this.onAdd != undefined) {
        this.onAdd(this.item_selected);
      }

      this.show_carregando = false;
      this.show_search = false;
      this.codigo = null;
      this.texto = "";
      this.item_selected = null;
    },
  },
};
</script>