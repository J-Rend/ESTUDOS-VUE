import axios from 'axios';
import Api from '../library/Api.js';
import base from './ServicoBase.js';


export default {

    getModel() {


            var form = {
                    
                id: null , 
                title: "", 
                description: "", 
                isbn: "", 
                stock: null , 
                author_id: null , 
                price: null, 
                price_txt: "", 

                editor: "", 
                created_at: null , 
                updated_at: null , 
                date_release: null ,
                date_release_txt: ""
            }

            return form;
    },

    getById(id) {

            return Api.Call("books/" + id, "get", {});

    },

    salvar(form, fn_progress) {


            if (form.id != null && form.id != "") {
                    // var data = base.removeColumns( form, "cadastrado_por,valido,verificado,eliminado");
                    return Api.Call("books/" + form.id, "PUT", form, fn_progress);
            } else {
                    return Api.Call("books", "POST", form, fn_progress);

            }

    },
    delete(id) {
            return Api.Call("books/" + id, "DELETE", {});
    },

    getList(filtro, order, order_type) {

            var data = { filtro: filtro, order: order, order_type: order_type }

            return Api.Call("books?" + base.serialize(data), "get", data);


    }

}
