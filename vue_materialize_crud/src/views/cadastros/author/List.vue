<template>
  <div>
    <div class="md-layout" v-bind:style="style_list()">
      <div class="md-layout-item">
        <md-card>
          <md-card-header
            class="md-card-header-icon"
            v-if="false"
            data-background-color="blue"
          >
            <div class="card-icon">
              <md-icon>assignment_ind</md-icon>
            </div>
            <h4 class="title">Author</h4>
          </md-card-header>
          <md-card-content>
            <div class="md-layout">
              <div
                class="md-layout-item md-size-70 md-xsmall-size-100"
                style="padding-left: 0px"
              >
                <filtro_simples
                  v-model="searchQuery"
                  placeholder="Filtrar por nome"
                  :disabled="carregando"
                  :onSearch="load_data"
                ></filtro_simples>
              </div>

              <div class="md-layout-item md-size-30 md-xsmall-size-100">
                <md-button
                  title="Adicionar"
                  class="md-round md-just-icon md-primary pull-right"
                  v-on:click="add_new"
                  type="button"
                >
                  <md-icon>add</md-icon>
                </md-button>
              </div>
            </div>

            <div v-if="carregando">
              <div class="md-table-head-container md-ripple md-disabled">
                <div class="md-table-head-label">
                  <i>Carregando..</i>
                </div>
              </div>
            </div>
            <table
              ref="table_data"
              class="table bordered striped display responsive"
              style="width: 100%"
            >
              <thead>
                <tr>
                  <th>ID</th>
                  <th data-priority="1">Nome do Autor</th>
                  <th>Data Cadastro</th>
                  <th data-priority="0" data-sorting data-width="40px"></th>
                </tr>
              </thead>
            </table>
          </md-card-content>
        </md-card>
      </div>
    </div>

    <div v-if="action == 'form'">
      <author_form
        v-bind:id_load="form.id"
        v-bind:onSave="onSave"
        show_back_button="true"
        v-bind:onBack="onBack"
      ></author_form>
    </div>
  </div>
</template>

<script>
import service from "../../../services/AuthorService";
import author_form from "./Form";
import filtro_simples from "../../components/filtro_simples.vue";
import Alerta from "../../../library/Alerta";
import $ from "jquery";
import objDataTable from "../../../library/ObjDataTable";
import Util from "../../../library/Util";

//let dtnet = require( 'datatables.net' )( window, $ );
//const $ = require('jquery');
//
//require('../../../library/dataTables/dataTables.material.min.js');
$.DataTable = require("datatables.net-responsive"); //require('datatables.net');
//require('../../../library/dataTables/dataTables.material.min.js')( window, $ );
export default {
  components: {
    author_form,
    filtro_simples,
  },
  computed: {},
  data() {
    return {
      currentSort: "name",
      currentSortOrder: "asc",
      carregando: true,
      searchQuery: "",

      action: "list",
      form: null,
      items: [],
      table: null,
    };
  },
  methods: {
    style_list() {
      if (this.action == "form") {
        return "display:none";
      }
      return "";
    },
    customSort(value) {
      // console.log(  this.currentSort +" - " +  this.currentSortOrder );
      this.load_data();
    },
    handleLike(item) {
      this.form = item;
      this.action = "form";
    },

    onBack(objPost) {
      //Clicou no back button.
      this.form = null; //Voltando para a lista
      this.action = "list";
      this.clean_back_button();
    },
    onSave(obj, acao) {
      this.load_data();

      if (acao == "save") {
        Alerta.show(
          "Sucesso!",
          "Autor salvo com sucesso!",
          "success",
          null,
          3000
        );
      }

      if (acao == "delete") {
        if ($ != null && $ != undefined) {
          var divs = $(".main-panel");
          // console.log(divs);
          if (divs != null && divs.length > 0) {
            //divs[0].scrollHeight
            $(divs[0]).scrollTop();
          }
        }
        //console.log("Consegui fazer o scroll?");

        Alerta.show(
          "Sucesso!",
          "Autor excluído com sucesso!",
          "success",
          function () {},
          3000
        );
      }
    },
    handleEdit(item) {
      this.form = item;
      this.action = "form";
    },
    editar(item) {
      // e.preventDefault();
      this.form = item;
      this.action = "form";
    },
    add_new(e) {
      e.preventDefault();
      this.form = service.getModel();
      this.action = "form";
    },
    writeTable(items, page) {
      let self = this;

      if (this.table == null) {
        var table = $(self.$refs["table_data"]).DataTable({
          //"dom" : "Bfrtip",
          pagingType: "full_numbers",
          language: objDataTable.getLanguage(),
          processing: true,
          lengthChange: false,
          searching: false,
          responsive: true,
          pageLength: 6,
          columns: [{ data: "id" }, { data: "name" }, { data: "updated_at" },{data: "id"}],
          order: [[1, "asc"]],
          columnDefs: [
            {
              render: function (data, type, row) {
                return '<button type="button" class="md-button md-just-icon md-simple md-primary md-theme-default"><div class="md-ripple"><div class="md-button-content"><i class="md-icon md-icon-font md-theme-default">edit</i></div> </div></button>';
              },
              targets: 3,
            },
            {
              render: function (data, type, row) {
                return (
                  '<span style="display:none">' +
                  data +
                  "</span>" +
                  Util.ApiDateToBR(data)
                );
              },
              targets: 2,
            },
          ],
          data: items,
        });

        var id_tabela = $(self.$refs["table_data"])[0].id;

        //console.log( " id_tabela ? ", id_tabela);

        $("#" + id_tabela + " tbody").on("click", "button", function () {
          var data = table.row($(this).parents("tr")).data();
          self.editar(data);
          //alert( data[0] +"'s salary is: "+ data[ 5 ] );
        });

        self.table = table;
      } else {
        self.table.clear().draw();
        self.table.rows.add(items); // Add new data

        if (page != null && page != undefined && page > 0) {
          // self.table.displayStart = page; //fnPageChange(page, true); //this.table.displayStart
          self.table.columns.adjust().draw(); // Redraw the DataTable
        } else {
          self.table.columns.adjust().draw();
        }
      }
    },
    load_data() {
      let self = this;

      var query = this.searchQuery;

      if (query == null) {
        query = "";
      }

      service
        .getList(query, this.currentSort, this.currentSortOrder)
        .then(function (response) {
          self.items = response.data.data;

          self.carregando = false;
          self.writeTable(self.items);
        });
    },
    enter(e) {
      e.preventDefault();

      this.load_data();
    },
    clean_back_button() {
      this.$store.dispatch("setButtonBack", {
        in_form: false,
        rota: "author",
        exibe: false,
        onBack: null,
      });
    },
  },
  mounted() {
    this.load_data();
  },
};
</script>

<style lang="css" scoped>
.md-card .md-card-actions {
  border: 0;
  margin-left: 20px;
  margin-right: 20px;
}
</style>
