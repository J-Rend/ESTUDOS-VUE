<template>
  <div>
    <div class="md-layout">
      <div class="md-layout-item md-size-100">
        <md-button
          class="md-dense md-primary"
          v-on:click="botao_voltar"
          v-if="false"
        >
          <md-icon>arrow_back</md-icon>Voltar para lista
        </md-button>

        <div class="md-layout" v-if="false && show_message == 'on'">
          <div class="alert alert-success">{{ message_text }}</div>
        </div>
      </div>
    </div>

    <div class="md-layout" v-if="loading">
      <div class="md-layout-item md-size-100">
        <md-progress-spinner
          :md-diameter="30"
          md-mode="indeterminate"
        ></md-progress-spinner>
        {{ msg_status }}
      </div>
    </div>

    <div class="md-layout">
      <div class="md-layout-item md-size-100">
        <md-card>
          <md-card-header
            class="md-card-header-text md-card-header-green"
            v-if="false"
          >
            <div class="card-text">
              <h4 class="title">Dados Básicos</h4>
            </div>
          </md-card-header>

          <md-card-content>
            <div class="md-layout">
              <div class="md-layout-item md-size-50">
                <label>Title</label>
                <md-field>
                  <md-input
                    name="title"
                    type="text"
                    placeholder="Title"
                    v-model="form.title"
                    maxlength="300"
                  ></md-input>
                </md-field>

                <span class="error_input">{{ getErrors("title") }}</span>
              </div>
              <div class="md-layout-item md-size-50">
                <label> ISBN</label>
                <md-field>
                  <md-input
                    name="isbn"
                    type="text"
                    placeholder="ISBN"
                    v-model="form.isbn"
                    maxlength="30"
                  ></md-input>
                </md-field>
              </div>

              <div class="md-layout-item md-size-50">
                <label> Author </label>
                <md-field>
                  <md-select
                    name="author_id"
                    id="author_id"
                    v-model="form.author_id"
                  >
                    <md-option v-bind:value="null">--SELECIONE</md-option>
                    <md-option
                      v-for="item in list_author_id"
                      :key="item.id"
                      v-bind:value="item.id"
                    >
                      {{ item.name }}
                    </md-option>
                  </md-select>
                </md-field>
              </div>

              <div class="md-layout-item md-size-50">
                <label> Price </label>
                <md-field>
                  <!--   id="price" v-bind:value="form.price | show_numero_real"
                                                                type="text" placeholder="Price" 
                                                                onkeypress="return obj_mask.Numerico(event, 2)" 
                                                                v-on:keypress="obj_mask.Numerico(event, 2)" -->

                  <md-input
                    name="price"
                    v-model="form.price_txt"
                    style="width: 110px"
                  />
                </md-field>
              </div>

              <div class="md-layout-item md-size-50">
                <label> Editor </label>
                <md-field>
                  <md-input
                    name="editor"
                    type="text"
                    placeholder="Editor"
                    v-model="form.editor"
                  ></md-input>
                </md-field>
              </div>

              <div class="md-layout-item md-size-50">
                <label> Released Date </label>
                <md-field>
                  <DatepickerHour
                    v-model="form.date_release"
                    id_data="date_release"
                    id_hora="date_release_hour"
                  ></DatepickerHour>
                </md-field>
              </div>
              <div class="md-layout-item md-size-50">
                <label class="md-layout-item md-size-15 md-form-label">
                  Stock
                </label>
                <md-field>
                  <md-input
                    name="stock"
                    type="number"
                    placeholder="Stock"
                    v-model="form.stock"
                    maxlength="4"
                  ></md-input>
                </md-field>
              </div>
            </div>

            <div class="md-layout">
              <div class="md-layout-item md-size-100">
                <label> Description </label>
                <md-field>
                  <!--     
                                                    <md-input  name="description"   type="text" placeholder="Description" v-model="form.description" ></md-input>
                                      :value.sync="value"
                                                    -->
                  <md-textarea
                    v-model="form.description"
                    name="text"
                    :debounce="500"
                    placeholder="Description"
                  ></md-textarea>
                </md-field>
              </div>
            </div>
          </md-card-content>
        </md-card>
      </div>

      <div class="md-layout-item md-size-100 text-right">
        <div class="btn-right">
          <md-button
            class="md-info"
            type="button"
            v-bind:disabled="disableButton"
            v-on:click="salvar"
            >Salvar</md-button
          >

          <md-button
            class="md-danger"
            type="button"
            v-bind:disabled="disableButton"
            v-if="
              form != null &&
              form.id != null &&
              form.id != '' &&
              parseInt(form.id) > 0
            "
            v-on:click="excluir"
            >Excluir</md-button
          >
        </div>
      </div>

      <md-dialog :md-active.sync="showDialogError">
        <md-tabs md-dynamic-height>
          <md-tab md-label="Erros">
            <ul class="list-group" v-if="p_validator.errors != null">
              <li
                v-for="(item, index) in p_validator.errors"
                class="list-group-item"
                :key="index"
                style="text-align: left"
              >
                {{ item.msg }}
              </li>
            </ul>
            <center>
              <md-button class="md-danger" type="button" v-on:click="hideModal"
                >OK</md-button
              >
            </center>
          </md-tab>
        </md-tabs>
      </md-dialog>
    </div>
    <div
      v-if="form.id != null && parseInt(form.id) > 0"
      class="md-layout-item md-size-100"
    >
      <uploadfolder
        :id_registro="form.id"
        titulo="Book"
        id_tabela="book"
      ></uploadfolder>
    </div>
  </div>
</template>

<style scoped>
@import "../../../assets/css/validate.css";
/* @import "../../../assets/css/form_custom.css"; */
</style>


<script>
import service from "../../../services/BookService";
import authorService from "../../../services/AuthorService";
//import autocomplete from "../../../components/Rend/autocomplete";
//import filelogo from "../../../components/Rend/filelogo";
import mixin_validator from "../../../library/mixin/validator";
import mixin_util from "../../../library/mixin/util";
import Alerta from "../../../library/Alerta";
import Util from "../../../library/Util";

import Datepicker from "../../../components/Rend/vuejs-datepicker/src/components/Datepicker";
import ptBR from "../../../components/Rend/vuejs-datepicker/src/locale/translations/pt-BR";

import DatepickerHour from "../../../components/Rend/DatepickerHour";
import $ from "jquery";
import uploadfolder from "../../components/images/uploadFolder";
//import modal from "../../../components/Modal";

export default {
  mixins: [mixin_util, mixin_validator],
  props: ["id_load", "show_back_button", "onBack", "onSave"],
  components: {
    Datepicker,
    DatepickerHour,
    uploadfolder,
  },

  data() {
    return {
      // obj_mask: Mask,

      acao: "Cadastrar",
      form: {},
      perfis: [],
      clientes: [],
      clientes_list: [],

      show_perfil: false,
      file_upload: null,

      botao_voltar_visible: true,

      loading: true,

      result2: "",
      disableButton: false,

      show_message: "off",
      message_text: "",
      message_type: "success",
      interval_message: null,
      showDialogError: false,

      msg_status: "Carregando",

      show_modal_contacts: false,
      show_sub_form: true,

      list_author_id: [],
      date_release: null,
      language_date: ptBR,
    };
  },
  mounted() {
    let self = this;
    this.form = service.getModel();

    this.configureMessages();

    if (this.show_back_button != null && this.show_back_button != undefined) {
      this.botao_voltar_visible = this.show_back_button;
    }

    this.disableButton = true;

    authorService.getList("", "name", "asc").then(function (response) {
      self.list_author_id = response.data.data; // Util.getListToSelect(  response.data.data, "id", "name", null );
    });

    if (
      this.id_load != null &&
      this.id_load != undefined &&
      this.id_load != ""
    ) {
      service.getById(this.id_load).then(function (response) {
        //console.log("response? ", response );
        self.form = response.data.data;
        $("#date_release").val(self.form.date_release_txt);
        $("#date_release_hour").val(self.form.date_release_hour_txt);
        self.loading = false;
        self.disableButton = false;
      });
    } else {
      this.loading = false;
      this.disableButton = false;
    }

    if (this.$store != null) {
      this.$store.dispatch("setButtonBack", {
        in_form: true,
        rota: "author",
        exibe: this.show_back_button,
        onBack: this.onBack,
      });
    }
    //this.$store.dispatch("user/save", this.tmpUser)
  },
  methods: {
    botao_voltar() {
      var self = this;

      if (this.onBack != null && this.onBack != undefined) {
        this.onBack(self);
      }
    },
    datepicker_inputchanged(value) {
      console.log("datepicker_inputchanged", value);
    },
    changed(prop) {},

    configureMessages() {
      const dict = {
        // email: {
        //   required: 'O e-mail nÃ£o pode ser vazio!',
        //   email: 'Ã necessÃ¡rio informar um e-mail vÃ¡lido!'
        // },
        title: {
          required: "Nome não pode ser vazio!",
          internal: true,
        },
        author_id: {
          required: "Selecione um autor!",
          internal: true,
        },
        // cnpj: {
        //   required: 'CPF/CNPJ nÃ£o pode ser vazio!',
        // cpf: true //testarÃ¡ o mÃ©todo de CPF
        // }
      };

      this.setFieldsValidator(dict);

      //this.p_validator.localize('en', dict);
    },
    onPreSaveContatos() {
      this.show_sub_form = false;
    },
    onPosSaveContatos(itens) {
      this.show_sub_form = true;
    },
    salvar() {
      var self = this;

      if (!this.validar()) return false;

      self.msg_status = "Salvando..";
      self.loading = true;

      this.form.date_release_txt = $("#date_release").val();
      this.form.date_release_hour_txt = $("#date_release_hour").val();

      service
        .salvar(this.form)
        .then(function (response) {
          var retorno = response.data;

          self.show_message = "on";
          self.message_text = "Autor salvo com sucesso!";

          setTimeout(function () {
            self.clear_message();
          }, 3000);

          var item = retorno.item;

          self.msg_status = "Carregando..";
          self.loading = false;

          self.form = item;

          if (self.onSave != null && self.onSave != undefined) {
            self.onSave(retorno, "save");
          }
        })
        .catch(function (error) {});
    },

    excluir() {
      let self = this;

      var fn_return = function (response) {
        var retorno = response.data;
        if (self.onSave != null && self.onSave != undefined) {
          self.onSave(retorno, "delete");
        }
        self.msg_status = "Carregando..";
        self.loading = false;
        self.botao_voltar();
      };
      if (this.form.id == null) return false;

      Alerta.confirm(
        "Atenção",
        "Deseja realmente excluir?",
        "question",
        function (res) {
          self.msg_status = "Excluindo..";
          self.loading = true;
          service.delete(self.form.id).then(fn_return);
        }
      );

      //	obj_api.call_delete("cliente", this.id, fn_return);
    },
    clear_message() {
      this.show_message = "off";
      clearInterval(this.interval_message);
    },
    hideModal() {
      this.showDialogError = false;
    },
    validar() {
      this.indicateAllErrors(); //Verifica todos os possÃ­veis erros em tela.
      console.log("erros? ");
      console.log(this.p_validator.errors);

      if (this.p_validator.errors.length > 0) {
        this.showDialogError = true;
        return false;
      }

      return true;
    },
  },
};
</script>
