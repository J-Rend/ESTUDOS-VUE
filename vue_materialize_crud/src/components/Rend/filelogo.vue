<template>
<div>

      <div class="md-layout">
            <label class="md-layout-item md-size-15 md-form-label">
                  <md-icon v-if="!get_show_img()">photo</md-icon>
                   <img v-if="get_show_img()" :src="getUrlImage()" alt="Logo">



            </label>
            <div class="md-layout-item">
              <md-field>
                <md-input  type="file" v-on:change="change" ></md-input>
                
              </md-field>


            </div>
          </div>
             
</div>
</template>

<script>

import Vue from 'vue';
export default {


          props: ['onChange', 'image_url','tipo'],
            data() {
                return {
                    show_img: false,
                    file: null,
                    url: "",
                    url_props: true,
                }
            },
           methods:{

               change(e){

                   if ( this.onChange != null ){
                       this.onChange(e);
                   }

                   if ( e.target.files != null ){
                       var oFile = e.target.files[0];
                       this.url = URL.createObjectURL(oFile);
                       this.show_img = true;
                       this.url_props = false; //não vou mais iesperar que coloquem uma url pela props.
                   }

               },
               get_show_img(){
                   if (this.url_props){
                       return ( this.image_url != null && this.image_url != undefined && this.image_url != "");

                   }else{

                       return this.show_img;
                   }
               },
               getUrlImage(){

                   if (this.url_props){
                                        console.log("Estou aqui? " + this.image_url );
                           if ( this.image_url != null && this.image_url != undefined && this.image_url != ""){
                                
                                        this.show_img = true;
                                        return this.image_url;
                                         
                            }

                   }

                   return this.url;

               }

           },
           mounted(){


           }


}
</script>