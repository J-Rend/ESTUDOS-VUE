export default {

        data() {
                return {

                        p_validator: { errors: [], fields: null },
                }
        },

        methods: {

                getFirstError(prop) {

                        if (this.p_validator.errors != null) {

                                for (var i = 0; i < this.p_validator.errors.length; i++) {
                                        if (this.p_validator.errors[i].field == prop) {
                                                return this.p_validator.errors[i].msg;
                                        }
                                }
                        }

                        return "";

                },

                removeErrorField(prop) {

                        for (var i = 0; i < this.p_validator.errors.length; i++) {
                                if (this.p_validator.errors[i].field == prop) {
                                        this.p_validator.errors.splice(i, 1)
                                        //return  this.p_validator.errors[i].msg;
                                }
                        }

                },
                getErrors(prop) {

                        return this.getFirstError(prop); // this.p_validator.errors.first(prop);

                },
                setErrors(prop, msg) {

                        this.p_validator.errors.push({ field: prop, msg: msg });
                },
                setFieldsValidator(fields) {

                        console.log("Jà existiu o validator?"); console.log(this.p_validator);

                        this.p_validator.fields = fields;
                },
                getInputByName(name) {

                        var inputs = document.getElementsByName(name);
                        //console.log( inputs );

                        for (var i = 0; i < inputs.length; i++) {
                                if (inputs[i].tagName.toLowerCase() == "input" || inputs[i].tagName.toLowerCase() == "select")
                                        return inputs[i];
                        }

                        return null;

                },

                errorTestKey(key) {

                        var fields = this.p_validator.fields;

                        var inputItem = this.getInputByName(key);

                        if (fields[key] == undefined) {
                                return;
                        }

                        //console.log("Tentando a key: " + key );
                        //console.log(inputItem);

                        if (fields[key].internal) {
                                inputItem = { value: this.form[key] != null ? this.form[key] : "" }

                        }


                        if (fields[key].required != null) {
                                if (inputItem != null) {
                                        var str = inputItem.value.toString().trim();

                                        if (str == "") {
                                                this.setErrors(key, fields[key].required);
                                                return;
                                        }
                                }
                        }

                        if (fields[key].email != null) {
                                if (inputItem != null) {
                                        var str = inputItem.value.trim();

                                        var filtro = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

                                        if (!filtro.test(str)) { //Faz uma validação mais completa na qualidade do email.
                                                this.setErrors(key, fields[key].email);
                                                return;

                                        }

                                        if (str.indexOf("@") < 0 || str.indexOf(".") < 0) {
                                                this.setErrors(key, fields[key].email);
                                                return;
                                        }
                                }
                        }

                        if (fields[key].email_and_cpf != null) {
                                if (inputItem != null) {
                                        var str = inputItem.value.trim();

                                        var filtro = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

                                        if (isNaN(str)) { //Se tudo for número, vou assumir que é um CPF

                                                if (!filtro.test(str)) { //Faz uma validação mais completa na qualidade do email.
                                                        this.setErrors(key, fields[key].email_and_cpf);
                                                        return;

                                                }

                                                if (str.indexOf("@") < 0 || str.indexOf(".") < 0) {
                                                        this.setErrors(key, fields[key].email_and_cpf);
                                                        return;
                                                }
                                        }
                                }

                        }



                        if (inputItem != null && fields[key].cpf != null) {


                                var str = inputItem.value.trim();
                                if (!this.errorTestCPF(str)) {
                                        this.setErrors("cpf", "CPF Inválido!");
                                        return;

                                }
                        }



                },


                indicateErrors(event) {

                        // console.log("Changed"); console.log(event);

                        if (event == null || event == undefined)
                                return false;

                        if (event.target == null || event.target == undefined) {
                                return false;

                        }

                        var name = event.target.name;

                        this.removeErrorField(name);



                        if (this.p_validator.fields != null) {
                                this.errorTestKey(name);
                        }
                },
                indicateErrorsByName(name) {

                        this.removeErrorField(name);



                        if (this.p_validator.fields != null) {
                                this.errorTestKey(name);
                        }
                },

                indicateAllErrors() {  //Vou indicar todos os erros possíveis..

                        var keys = Object.keys(this.p_validator.fields);


                        for (var i = 0; i < keys.length; i++) {
                                var name = keys[i];

                                this.removeErrorField(name);
                                this.errorTestKey(name);
                        }

                },


                errorTestCPF(strTmpCPF) {
                        var Soma;
                        var Resto;
                        Soma = 0;

                        var numb = strTmpCPF.match(/\d/g);
                        var strCPF = numb.join("");



                        if (strCPF == "00000000000") return false;

                        for (var i = 1; i <= 9; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
                        Resto = (Soma * 10) % 11;

                        if ((Resto == 10) || (Resto == 11)) Resto = 0;
                        if (Resto != parseInt(strCPF.substring(9, 10))) return false;

                        Soma = 0;
                        for (var i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
                        Resto = (Soma * 10) % 11;

                        if ((Resto == 10) || (Resto == 11)) Resto = 0;
                        if (Resto != parseInt(strCPF.substring(10, 11))) return false;
                        return true;
                }

        }

}