<template>
  <div>
    <CardCadastro size="100">
      <div slot="header">
        <h4 class="title">Galeria de Mídia / Arquivos</h4>
      </div>
      <div slot="body">
        <h5 v-html="titulo"></h5>
        <md-button
          class="md-default"
          v-if="false"
          v-on:click="botao_mostra_upload"
        >Adicionar Arquivos</md-button>

        <upload :p_parentid="id_registro" :ptype="id_tabela" v-bind:onSave="onSave"></upload>

        <div class="md-layout" v-if="loading">
          <div class="md-layout-item md-size-100">
            <md-progress-spinner :md-diameter="30" md-mode="indeterminate"></md-progress-spinner>
            {{msg_status}} ..
          </div>
        </div>
        <div class="md-layout">
          <div v-for="(item, index) in items " :key="index" class="md-layout-item md-size-25">
            <div v-if="item.type.indexOf('image') > -1 ">
              <img v-bind:src="item.url_thumb" style="max-height: 70px; max-width: 70px; " />
            </div>
            <div v-if="item.type.indexOf('image') < 0 ">
              <md-icon style="font-size: 70px">attach_file</md-icon>
            </div>
            <a :href="item.url" target="_blank">{{item.arquivo}}</a>
            <a href="#!" v-on:click="remover(item, index)">
              <md-icon>delete</md-icon>
            </a>
          </div>
        </div>
      </div>
    </CardCadastro>
  </div>
</template>

<script>
import upload from "./upload.vue";
import CardCadastro from "../../../components/CardCadastro";
import service from "../../../services/FileService";
import Alerta from "../../../library/Alerta";

import $ from "jquery";

export default {
  components: { upload, CardCadastro },
  props: [
    "id_registro",
    "id_tabela",
    "pShowUpload",
    "unique_id",
    "titulo",
    "onLoadData"
  ],

  data: function() {
    return {
      action: "list",
      items: [],
      id: "",
      index_selecionado: "",
      texto_pesquisa: "",

      loading: false,
      msg_status: "Carregando",
      v_visivel_upload: false,
      img_loading: "loading.gif"
    };
  },
  mounted() {
    this.loadData();
  },
  methods: {
    loadData() {
      var self = this;
      self.loading = true;

      service
        .getList(this.id_registro, this.id_tabela)
        .then(function(response) {
          console.log(response.data);
          self.items = response.data;
          self.loading = false;

          if (self.onLoadData != null) {
            self.onLoadData(response.data);
          }
        });
    },

    getID(tip) {
      return this.unique_id + tip;
    },

    show_botao_upload() {
      if (this.pShowUpload != null && this.pShowUpload == "false") {
        return false;
      }

      return true;
    },
    show_upload() {
      return this.v_visivel_upload;
    },
    botao_mostra_upload() {
      this.v_visivel_upload = !this.v_visivel_upload;
    },

    style_list() {
      if (this.action == "form" || this.action == "formsel") {
        return "display:none";
      }
      return "";
    },

    onDelete(id) {
      this.loadData();
    },
    onSave(data, tipo) {
      var self = this;
      if (tipo == "upload") {
        self.loadData();
      }
    },
    remover(item, index) {
      var self = this;

      Alerta.confirm("Atenção", "deseja realmente excluir este arquivo?", "question", function(res){
        service.delete(item.id).then(function(response) {
                self.items.splice(index, 1);
              });

      });

    }
  }
};
</script>