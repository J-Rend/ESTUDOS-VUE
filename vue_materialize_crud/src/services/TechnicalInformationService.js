import axios from 'axios';
import Api from '../library/Api.js';
import base from './ServicoBase.js';

export default {

      getModel(){   


      	    var form = {
                      id: null , 
 id_book: null , 
 page_number: "", 
 weight: "", 
 created_at: null , 
 updated_at: null , 
      	    }

      	    return form;
      },

      getById(id){

                  return Api.Call("technical_information/" + id + "", "get", {});

      },

      salvar(form, fn_progress){
            
            
            if ( form.id != null && form.id != ""){
                 // var data = base.removeColumns( form, "cadastrado_por,valido,verificado,eliminado");
                  return Api.Call("technical_information/" + form.id + "", "PUT", form, fn_progress );
            } else {

                  
                return Api.Call("technical_information" , "POST", form, fn_progress);


            }      

      },
      delete(id){
            return Api.Call("technical_information/" + id + "", "DELETE", {} );
      },
      filtrar(page, search ){

      	var data = {filtro: search , page: page, pagesize: process.env.VUE_APP_PAGESIZE}

      	return Api.Call( "technical_information_grid" , "get", data );


     },
     
     getList(filtro,  order, order_type){

        var data = {filtro: filtro,  order: order, order_type: order_type}

        return Api.Call( "technical_information?" + base.serialize(data) , "get", data );


     },
	 
     getList2(data){


        return Api.Call( "technical_information?" + base.serialize(data) , "get", data );


     },
	  getListByParent( tabela, id_pai  ){

      
        var data = {tabela: tabela,  id_pai: id_pai }

      	return Api.Call( "technical_information_gridcad?" + base.serialize(data) , "get", data );


     },
	 
	 salvarGrid( hd_json, ids_delete_json, tabela, id_pai ){

        var data = {
            hd_json: hd_json,
            ids_delete_json: ids_delete_json,
            tabela: tabela,
            id_pai: id_pai
        };

        return Api.CallFormData("technical_information_salvargrid" , "POST", data);
        
     }


}
