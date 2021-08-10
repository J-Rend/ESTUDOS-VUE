import axios from 'axios';
import Api from '../library/Api.js';
import base from './ServicoBase.js';

export default {

        getModel() {


                var form = {
                        id: null,
                        name: "",
                }

                return form;
        },

        getById(id) {

                return Api.Call("authors/" + id, "get", {});

        },

        salvar(form, fn_progress) {


                if (form.id != null && form.id != "") {
                        // var data = base.removeColumns( form, "cadastrado_por,valido,verificado,eliminado");
                        return Api.Call("authors/" + form.id, "PUT", form, fn_progress);
                } else {


                        return Api.Call("authors", "POST", form, fn_progress);


                }

        },
        delete(id) {
                return Api.Call("authors/" + id, "DELETE", {});
        },
        filtrar(page, search) {

                var data = { filtro: search, page: page, pagesize: process.env.VUE_APP_PAGESIZE }

                return Api.Call("authors_grid", "get", data);


        },

        getList(filtro, order, order_type) {

                var data = { filtro: filtro, order: order, order_type: order_type }

                return Api.Call("authors?" + base.serialize(data), "get", data);


        },



}
