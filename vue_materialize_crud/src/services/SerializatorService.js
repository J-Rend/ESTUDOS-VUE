import axios from 'axios';
import Api from '../library/Api.js';
import base from './ServicoBase.js';

export default {

        getModel() {


                var form = {
                        id: null,
                        data_cadastro: null,
                        data_value: "",
                        data_type: "",
                        variableName: "",
                        pageGuid: "",
                        user_name: "",
                        id_user: null,
                        url: "",
                }

                return form;
        },

        getById(id) {

                return Api.Call("serializator/" + id + "", "get", {});

        },

        salvar(form, fn_progress) {


                if (form.id != null && form.id != "") {
                        // var data = base.removeColumns( form, "cadastrado_por,valido,verificado,eliminado");
                        return Api.Call("serializator/" + form.id + "", "PUT", form, fn_progress);
                } else {


                        return Api.Call("serializator", "POST", form, fn_progress);


                }

        },
        delete(id) {
                return Api.Call("serializator/" + id + "", "DELETE", {});
        },
        filtrar(page, search) {

                var data = { filtro: search, page: page, pagesize: process.env.VUE_APP_PAGESIZE }

                return Api.Call("serializator_grid", "get", data);


        },

        getList(filtro, order, order_type) {

                var data = { filtro: filtro, order: order, order_type: order_type }

                return Api.Call("serializator?" + base.serialize(data), "get", data);


        },

        getListByParent(tabela, id_pai) {


                var data = { tabela: tabela, id_pai: id_pai }

                return Api.Call("serializator_gridcad?" + base.serialize(data), "get", data);


        },

        salvarGrid(hd_json, ids_delete_json, tabela, id_pai) {

                var data = {
                        hd_json: hd_json,
                        ids_delete_json: ids_delete_json,
                        tabela: tabela,
                        id_pai: id_pai
                };

                return Api.CallFormData("serializator_salvargrid", "POST", data);

        }


}
