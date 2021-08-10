<template>
   <div class="md-layout text-center" >
    <div
      class="md-layout-item md-size-100 md-medium-size-50 md-small-size-70 md-xsmall-size-100" 
    >
      <div v-if="msg_show" v-bind:class="msg_class">
        <h6>{{msg_titulo}}</h6>
        <p>{{msg_texto}}</p>
      </div>
      <login-card>
        <h4 slot="title" class="title">
          <img class="img-responsive logo" :src="require('../../assets/images/logo.png')" alt="RendTI" />
        </h4>
        <md-field class="md-form-group" slot="inputs">
          <md-icon>email</md-icon>
          <label>Digite seu login</label>
          <md-input
            v-model="email"
            type="email"
            name="login_email"
            @focus="setInteraction('email','focus')"
            @blur="setInteraction('email','blur')"
            v-on:keyup="indicateErrors"
          ></md-input>
          <span class="error_input">{{ getErrors('login_email') }}</span>
        </md-field>
        <md-field class="md-form-group" slot="inputs">
          <md-icon>lock_outline</md-icon>
          <label v-if="false">Digite sua senha</label>
          <input
            type="password"
            id="md-input-password"
            v-model="password"
            @focus="setInteraction('password','focus')"
            @blur="setInteraction('password','blur')"
            v-on:keyup="key_senha"
            name="login_password"
            class="md-input"
            placeholder="Digite sua senha"
          />
          <md-input
            v-if="false"
            v-model="password"
            name="login_password"
            type="password"
            @focus="setInteraction('password','focus')"
            @blur="setInteraction('password','blur')"
            v-on:keyup="key_senha"
          ></md-input>
          <span class="error_input">{{ getErrors('login_password') }}</span>
        </md-field>
        <md-button
          slot="footer"
          type="button"
          v-on:click="entrar"
          class="md-primary md-lg btn-block"
        >Entrar</md-button>
      </login-card>
      <p>

      <b>Login: </b> admin@admin.com <br>
      <b>Senha: </b> admin


      </p> 
    </div>
  </div>
</template>

<script>
import { LoginCard } from "@/components";
import mixinvalidator from "../../library/mixin/validator.js";
import mixinuser from "../../library/mixin/user.js";
import Api from "../../library/Api";

export default {
  components: {
    LoginCard
  },
  mixins: [mixinuser, mixinvalidator],
  data() {
    // @keyup.enter="senha_enter"
    return {
      firstname: null,
      email: null,
      password: null,
      activeField: "",
      forca_validacao: false,
      msg_show: false,
      msg_class: "alert alert-danger",
      msg_titulo: "",
      msg_texto: ""
    };
  },
  mounted() {
    //window.obj_api.token_user = null;
    this.configureMessages();

    if (this.$route.query != null && this.$route.query.out != null && this.$route.query.out.toString() == "1" ){
      this.removeGlobalUser(); 
    }
  },

  methods: {
    setInteraction(id, interaction) {
      if (interaction == "focus") {
        this.activeField = id;
      } else {
        this.activeField = "";
      }

      this.forca_validacao = false; //Indica que tem alguém tentando cadastrar algo.
    },
    setMensagem(titulo, texto, classe) {
      this.msg_show = true;
      this.msg_class = "alert alert-" + classe;
      this.msg_titulo = titulo;
      this.msg_texto = texto;
    },

    configureMessages() {
      const dict = {
        login_email: {
          required: "O login não pode ser vazio!"
          //email_and_cpf: 'É necessário informar um e-mail válido!'
        },
        login_password: {
          required: "A senha não pode ser vazia!"
        }
      };

      this.setFieldsValidator(dict);
    },
    entrar() {
      var self = this;
      this.alert_show = false;

      this.indicateAllErrors(); //Verifica todos os possíveis erros em tela.

      if (this.p_validator.errors.length > 0) {
        return;
      }

      //console.log(this);
      //return;

      this.setMensagem("", "Realizando o login", "info");

      Api.CallPublic("login", "post", {
        email: this.email,
        password: this.password
      })
        .then(function(response) {
          var res = response.data;

          self.setMensagem(
            "Sucesso",
            "Redirecionando para tela de login",
            "success"
          );

          self.setGlobalUser(res); //datauser
          self.setLastLogin(self.email);
          self.$router.push({ path: "/" });
        })
        .catch(function(error) {
          let error_tipo = "geral";
          if (error.request) {
            error_tipo = "request";
          }
          if (error.response) {
            error_tipo = "request";
          }
          //+ JSON.stringify(error)

          self.setMensagem("Erro", "Login e senha não encontrado!", "danger");
        });
    },
    key_senha(event) {
      //console.log("Key senha");
      //console.log(event);
      if (event.key == "Enter") {
        this.entrar();
      }

      this.indicateErrors();
    },
    senha_enter() {
      this.entrar();
    }
  }
};
</script>