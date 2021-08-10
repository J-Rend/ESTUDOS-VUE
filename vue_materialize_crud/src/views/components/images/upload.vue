<template>
 <div>
   <div  v-bind:class="getClassName('md-layout')"  >
			   <div v-bind:class="getClassName('md-layout-item md-size-100')">
		
                <div id="div_drag" v-bind:style="getStyleDrop()">
                         {{getMsgInicial()}}
                </div>         
                    
                
      <div class="md-layout mb-20">

                        <div class="md-layout-item md-size-50">

	                   <label for="file_uploads"> Arquivos </label>
                           <input type="file" name="file_uploads" value="Selecionar arquivos"
                            multiple="multiple" 
                            id="file_uploads" @change="onFileChange" >
                        </div>
                        
                        <div class="md-layout-item md-size-50" style="text-align: right" v-if="hide_button == undefined || hide_button == null || hide_button == '0'">

                                     <md-button class="md-info"  type="button" 
									  v-on:click="sendUpload"> <span v-html="textUpload"></span></md-button>

                             </div>     




                </div>

			</div>
   </div>
</div>

</template>
<style>
#div_drag{
    width:99%;
    height:100px;
    line-height:100px;
    border:5px dashed #CCC;

    font-family:Verdana;
    text-align:center;
}

</style>

<script>

import service from '../../../services/FileService';
import $ from 'jquery';
import Api from '../../../library/Api';
import ApiJquery from '../../../library/ApiJquery';
import FormData from 'form-data';

    export default {
        props: ['onSave', 'ptype' , 'p_parentid', 'show_drop','msg_tamanho', 'onGetFiles', 'hide_button'],

        data: function() {
            return {

              action: "list",
            	items: [],
                id: "",
                files: [],
                files_input: [],

                disable_button: false,
                textUpload: "Upload"
            }
        },
        methods:{

        	       getMsgInicial(){
        	       	let msg = "";
        	       	
        	        if (  this.msg_tamanho != null && this.msg_tamanho != undefined ){
        	          	console.log("MSG TAMANHO: " + this.msg_tamanho );
        	        	msg = this.msg_tamanho;
        	        }	 
                        return  "ARRASTE ARQUIVOS AQUI " + msg;
        	       },
        	       getClassName(typ){
                           if ( this.show_drop != null && this.show_drop != undefined && this.show_drop == "0" ){
                          	  return "";
                          }
                          return typ;
        	       },

        	       getStyleDrop(){
                          if ( this.show_drop != null && this.show_drop != undefined && this.show_drop == "0" ){
                          	  return "display:none";
                          }
                          return "";
        	       },
                   upload(files){

                       this.files = files;

		                   	if ( files.length == 1){
							    //alert('Upload '+files.length+' File(s).');
							    console.log(files[0]);
							    $('#div_drag').html(files[0].name );
		                   	}else if ( files.length > 1 && files.length <= 10){

                                                var str = "";
		                   		for ( var i = 0; i < files.length; i++){
		                   			if ( str != "")
		                   				str += ", ";

                                                          str += files[i].name;


							         $('#div_drag').html(str );
		                   		}

		                   	}else if ( files.length > 10 ){
							    $('#div_drag').html(files.length.toString() +" arquivo(s) " );
		                   	}else{
		                   		 $('#div_drag').html(this.getMsgInicial() );
		                   	}
                   },
	           sendUpload(){

	                   	    if ( this.files.length <= 0  && this.files_input.length <= 0 )
                                               return;
                                               
                                     var data = {};
                                       var formData = new FormData();
                                       
                                       var qtde = 0;
                                       console.log("quantidades? ", this.files.length , this.files_input.length );
					                for (var x = 0; x < this.files.length; x++) {
					                    console.log("adicionando file " + x, this.files[x] );
                                                            formData.append("file" + x, this.files[x]); // this.files[x].name 
                                                            data["file" + x] = this.files[x];
					                    qtde++;
                                    }
                                    
                                    
					                 for (var x = 0; x < this.files_input.length; x++) {
					                    console.log("adicionando file " + x, this.files_input[x] );
					                                        formData.append("file" + qtde, this.files_input[x] ); //this.files_input[x].name
                                                            data["file" + qtde] = this.files_input[x];
					                    qtde++;
					                }

					                let p_type = "midia";

					                if ( this.ptype != null && this.ptype != undefined ){
					                	p_type = this.ptype;
					                }

	                                                this.disable_button = true;
					                this.textUpload = "Enviando..";

					                var file_qtde = this.files.length + this.files_input.length;

                                                        
                                                       formData.append("parent_id", this.p_parentid ); 
                                                       formData.append("file_qtde", qtde); //file_qtde.toString() );  
                                                       formData.append("type_image", p_type ); 
                                                       formData.append("tipo", p_type ); 


                                                       data["parent_id"] = this.p_parentid; 
					                                   data["file_qtde"] = qtde.toString();
                                                       data["type_image"] = p_type;
                                                       data["tipo"] = p_type;

                                                     //   console.log( JSON.stringify(  data ) );
                                                     //   console.log( data );
	                            
                                       let self = this;

                                       Api.CallFormData("files", "POST", formData,
                                             function (percentual){
                                                     self.progress(percentual)
                                             } ).then(function(response){


                                        if ( self.onSave != null && self.onSave != undefined ){
                                                self.onSave(retorno, 'upload');
                                        }

                                        self.disable_button = false;
			        	                self.textUpload = "Upload";

			        	                if ( self.files_input != null ){
                                                                          self.files_input  = [];
			        	                }			        	                           

                                        if ( self.files != null && self.files != undefined){
                                                                self.files = [];
                                        }

		                   		          $('#div_drag').html("ARRASTE ARQUIVOS AQUI");

                                                          $("#file_uploads").val("");

                                             }).catch(function(error){

                                                     if ( error.config != null ){

                                                     error.config.headers["Content-Type"] ='multipart/form-data';

                                                     ApiJquery.call(error.config, function (msg){
                                                             console.log("Deu erro com axios e com jquery deu certo! ");
                                                             console.log(msg);
                                                     }, formData);
                                                     }
                                                     else{
                                                             console.log( JSON.parse(  JSON.stringify(error ) ));
                                                     }


                                             });
                                       

	               },

		        onFileChange(e) {
                    console.log("target? ", e );
				  var files = e.target.files || e.dataTransfer.files;
				  if (!files.length)
				    return;

				   this.files_input.splice(0, this.files_input.length);

				   for ( var i = 0; i < files.length; i++){
				   	       this.files_input.push( files[i]);
                   }
                                   
                                   if ( this.onGetFiles != null &&   this.onGetFiles != undefined ){
                                          this.onGetFiles(  this.files_input )  ;
                                   }

				  //this.createImage(files[0]);
				},


                                progress (Percentage){
                                        var self  = this;

                                        
                                                this.textUpload = Percentage.toString() + "% enviado.";
                                                if(Percentage >= 100)
                                                {
                                                        this.textUpload = "Envio concluído. Salvando arquivos..";


                                                        
                                                          if ( self.onSave != null && self.onSave != undefined ){
							       self.onSave(null, 'upload');
							  }

                                                          self.disable_button = false;
			        	                  self.textUpload = "Upload";

			        	                  if ( self.files_input != null ){
                                                                          self.files_input  = [];
			        	                  }			        	                           

                                                          if ( self.files != null && self.files != undefined){
                                                                self.files = [];
                                                          }

		                   		          $('#div_drag').html("ARRASTE ARQUIVOS AQUI");

                                                          $("#file_uploads").val("");
                                                // process completed  
                                                } 
                                },

                                setFilesInput( obj ){
                                        let files = obj.files;

                                        this.files_input = files;         
                                } 
        },

        mounted() {

        	let self = this;

					$('#div_drag').on(
					    'dragover',
					    function(e) {
					        e.preventDefault();
					        e.stopPropagation();
					    }
					)
					$('#div_drag').on(
					    'dragenter',
					    function(e) {
					        e.preventDefault();
					        e.stopPropagation();
					    }
					)
					$('#div_drag').on(
					    'drop',
					    function(e){
					        if(e.originalEvent.dataTransfer){
					            if(e.originalEvent.dataTransfer.files.length) {
					                e.preventDefault();
					                e.stopPropagation();
					                /*UPLOAD FILES HERE*/
					                self.upload(e.originalEvent.dataTransfer.files);
					            }   
					        }
					    }
					);
					

        }
   

}


 </script>       
