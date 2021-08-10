<template>
  <span>
    <table border="0" class="autocomplete_table" style="border-collapse: none !important">
      <tr>
        <td>
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

        <td style="width: 80px" valign="top" v-if="false">
          <md-field>
            <input v-model="codigo" type="text" class="md-input" autocomplete="off" />
          </md-field>
        </td>
        <td valign="top" v-if="false">
          <md-field>
            <input
              type="text"
              v-model="texto"
              class="md-input"
              autocomplete="off"
              placeholder="Digite para pesquisar.."
              @keyup="change_value"
            />
          </md-field>
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
                <a style="cursor:pointer" v-on:click="seleciona(item)">
                  <span class="md-list-item-text">{{item[field_text]}}</span>
                </a>
              </li>
            </ul>
          </div>
        </td>
        <td style="width: 110px" v-if="show_add_button">
          <md-button class="md-primary" @click.native="add">
            <md-icon>add</md-icon>Adicionar
          </md-button>
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
export default {
  props: [
    "field_value",
    "field_text",
    "search_method",
    "exclude_itens",
    "show_add",
    "onSelect",
    "onAdd",
    "id_start_value",
    "add_filtro"
  ],
  components: {
    vue_select
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
      timeout: null
    };
  },

  mounted() {
    var self = this;

    if (this.show_add != null && this.show_add != undefined) {
      this.show_add_button = this.show_add;
    }

    if (
      this.id_start_value != null &&
      this.id_start_value != undefined &&
      self.search_method != null &&
      self.search_method != undefined &&
      false
    ) {
      console.log("start value? " + this.id_start_value);

      Api.Call(self.search_method + "/" + this.id_start_value, "GET", {})
        .then(function(response) {
          if (response.data.item != null) {
            var item = response.data.item;

            self.item_selected = item;
            console.log("Posso montar? ");
            console.log(self.item_selected);
            self.show_search = false;
            self.codigo = item[self.field_value];
            self.texto = item[self.field_text];
          }
        })
        .catch(function(error) {
          console.log("Erro no autocomplete: ");
          console.log(error);
        });
    }

    var add_filtro =""; // this.add_filtro;

    if ( this.add_filtro != null && this.add_filtro != undefined ){
             add_filtro = "?a=111"  + this.add_filtro;
    }

  //  console.log("search method? " + self.search_method + add_filtro  );

    Api.Call(self.search_method + add_filtro, "GET", {})
      .then(function(response) {
        if (response.data.data != null && response.data.data != undefined) {
          self.items = Util.getListToSelect(response.data.data, "id", "nome"); // response.data.data;
          self.items_busca = response.data.data;
        } else {
          self.items = Util.getListToSelect(response.data, "id", "nome");
          self.items_busca = response.data;
        }
      })
      .catch(function(error) {
        console.log("Erro no autocomplete: ");
        console.log(error);
      });
  },
  methods: {
    change_value(e) {
      var self = this;

      if (this.timeout != null) {
        clearTimeout(this.timeout);
      }

      this.timeout = setTimeout(function() {
        if (self.texto.length >= 3) {
          self.show_carregando = true;

          var filtr_exclude = ""; 
          var filtro_add = "";

          if (self.exclude_itens != null && self.exclude_itens != undefined) {
            self.ids_exclude = self.exclude_itens.map(a => a.id);

            filtr_exclude = "&exclude=" + self.ids_exclude.join(",");
          }

          if ( self.add_filtro != null && self.add_filtro != undefined  ){
            filtro_add = self.add_filtro;
          }

          Api.Call(
            self.search_method + "?filtro=" + self.texto + filtr_exclude + filtro_add,
            "GET",
            {}
          ).then(function(response) {
            self.items = response.data;
            self.show_search = true;
            self.show_carregando = false;
          });
        } else {
          self.show_search = false;
          self.show_carregando = false;
        }

        if (this.texto == "") {
          this.codigo = "";
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
    seleciona(item) {
      console.log("seleciona?");
      console.log(item);
      if (item == null || item == undefined) {
        this.codigo = "";
        this.texto = "";
        return;
      }

      var p_item = this.buscarbyid(item);

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
    }
  }
};
</script>