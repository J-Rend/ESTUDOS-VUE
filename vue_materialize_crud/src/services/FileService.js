import axios from 'axios';
import Api from '../library/Api.js';
import base from './ServicoBase.js';

export default {

      getModel(){   


      	    var form = {
                            id: null , 
                            name: "", 
                            type: "", 
                            table: "", 
                            title: "", 
                            size: "", 
                            id_registry: null , 
                            old_name: "", 
                            thumbs: "", 
                            pid: null 
      	    }

      	    return form;
      },

      getById(id){

                  return Api.Call("files/" + id + "", "get", {});

      },

      salvar(form, fn_progress){
            
            
            if ( form.id != null && form.id != ""){
                 // var data = base.removeColumns( form, "cadastrado_por,valido,verificado,eliminado");
                  return Api.Call("files/" + form.id + "", "PUT", form, fn_progress );
            } else {

                  
                return Api.Call("files" , "POST", form, fn_progress);


            }      

      },
      delete(id){
            return Api.Call("files/" + id + "", "DELETE", {} );
      },
     
     getList(id_registro, id_tabela){

        var data = {id_registro: id_registro,  id_tabela: id_tabela}

        return Api.Call( "files?" + base.serialize(data) , "get", data );


     },
	 
	  getListByParent( tabela, id_pai  ){

      
        var data = {tabela: tabela,  id_pai: id_pai }

      	return Api.Call( "files_gridcad?" + base.serialize(data) , "get", data );


     }


}
