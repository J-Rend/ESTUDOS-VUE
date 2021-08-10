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
      <div class="md-layout-item md-size-50">
        <md-card>
          <md-card-header class="md-card-header-text md-card-header-green">
            <div class="card-text">
              <h4 class="title">technical_information</h4>
            </div>
          </md-card-header>

          <md-card-content>
            <div class="md-layout">
              <div class="md-layout-item md-size-80">
                <md-field>
                  <label> Livro </label>

                  <md-select name="id_book" id="id_book" v-model="form.id_book">
                    <md-option
                      v-for="item in lists.id_book"
                      :key="item.id"
                      v-bind:value="item.id"
                    >
                      {{ item.descricao }}
                    </md-option>
                  </md-select>
                </md-field>

                <span class="error_input">{{ getErrors("id_book") }}</span>
              </div>
            </div>

            <div class="md-layout">
              <div class="md-layout-item md-size-80">
                <md-field>
                  <label> Número de páginas </label>

                  <md-input
                    name="page_number"
                    type="text"
                    placeholder="Número de páginas"
                    v-model="form.page_number"
                  ></md-input>
                </md-field>

                <span class="error_input">{{ getErrors("page_number") }}</span>
              </div>
            </div>

            <div class="md-layout">
              <div class="md-layout-item md-size-80">
                <md-field>
                  <label> Peso </label>

                  <md-input
                    name="weight"
                    type="text"
                    placeholder="Peso"
                    v-model="form.weight"
                  ></md-input>
                </md-field>

                <span class="error_input">{{ getErrors("weight") }}</span>
              </div>
            </div>
          </md-card-content>
        </md-card>
      </div>

      <div class="md-layout-item md-size-100">
        <div class="md-layout-item md-size-100" style="text-align: right">
          <md-button
            class="md-info"
            type="button"
            v-bind:disabled="disableButton"
            v-on:click="salvar"
          >
            Salvar</md-button
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
              <md-button class="md-danger" type="button" @click="hideModal"
                >OK</md-button
              >
            </center>
          </md-tab>
        </md-tabs>
      </md-dialog>
    </div>
  </div>
</template>

<style scoped>
@import "../../../assets/css/validate.css";
@import "../../../assets/css/form_custom.css";
</style>
<script>
import service from "../../../services/TechnicalInformationService";
import autocomplete from "../../../components/Rend/autocomplete";
import filelogo from "../../../components/Rend/filelogo";
import mixin_validator from "../../../library/mixin/validator";
import mixin_util from "../../../library/mixin/util";
import modal from "../../../components/Modal";

import Datepicker from "../../../components/Rend/vuejs-datepicker/src/components/Datepicker";
import ptBR from "../../../components/Rend/vuejs-datepicker/src/locale/translations/pt-BR";

import CardCadastro from "../../../components/CardCadastro";
import vue_select from "../../../components/Rend/vue-select/src/Select.vue";

import Util from "../../../library/Util";
import Alerta from "../../../library/Alerta";
//import sel_cliente from "../../cadastros/clientes/components/sel_cliente.vue";

export default {
  components: {
    autocomplete,
    filelogo,
    modal,
    Datepicker,
    CardCadastro,
    vue_select,
    //sel_cliente,
  },

  mixins: [mixin_util, mixin_validator],
  props: ["id_load", "show_back_button", "onBack", "onSave"],
  data() {
    return {
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

      language_date: ptBR,

      lists: {
        id_book: [],
      },
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

    if (
      this.id_load != null &&
      this.id_load != undefined &&
      this.id_load != ""
    ) {
      service.getById(this.id_load).then(function (response) {
        if (response.data.data != null && response.data.data != undefined) {
          self.form = response.data.data;
        } else {
          self.form = response.data.item;
        }

        self.loading = false;
        self.disableButton = false;
      });
    } else {
      this.loading = false;
      this.disableButton = false;
    }

    this.$store.dispatch("setButtonBack", {
      in_form: true,
      rota: "technical_information",
      exibe: this.show_back_button,
      onBack: this.onBack,
    });
    //this.$store.dispatch("user/save", this.tmpUser)
  },
  methods: {
    botao_voltar() {
      var self = this;

      if (this.onBack != null && this.onBack != undefined) {
        this.onBack(self);
      }
    },
    changed(prop) {},

    configureMessages() {
      const dict = {
        // email: {
        //   required: 'O e-mail não pode ser vazio!',
        //   email: 'É necessário informar um e-mail válido!'
        // },
        nome: {
          required: "Nome não pode ser vazio!",
        },
        // cnpj: {
        //   required: 'CPF/CNPJ não pode ser vazio!',
        // cpf: true //testará o método de CPF
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

    datepicker_inputchanged(ev) {},
    salvar() {
      var self = this;

      if (!this.validar()) return false;

      self.msg_status = "Salvando..";
      self.loading = true;

      service
        .salvar(this.form)
        .then(function (response) {
          var retorno = response.data;

          self.show_message = "on";
          self.message_text = "technical_information salvo com sucesso!";

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
      self.msg_status = "Excluindo..";
      self.loading = true;

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
    },
    clear_message() {
      this.show_message = "off";
    },
    hideModal() {
      this.showDialogError = false;
    },
    validar() {
      this.indicateAllErrors();
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
