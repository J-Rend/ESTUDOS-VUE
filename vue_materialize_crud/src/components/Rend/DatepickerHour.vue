<template>
 <span>
     <table>
  <tr>
   <td>
 <datepicker format="dd/MM/yyyy" :typeable=true inputClass="md-input"
                                                  :name="id_data" v-model="data"
                                                :language="language_date" calendar-button-icon-content="event"
                                                :input="datepicker_inputchanged"
                                                placeholder="Data Início"
                                                ></datepicker>
   </td>
   <td>

                   <input  :id="id_hora" style="width: 90px"
                           type="text" placeholder="Hora" v-model="hora" maxlength="5" 
                            class="md-input pull-left"  >                           

   </td>
  </tr>
     </table>
   

                 <!--
                 <md-input style="width: 90px" type="text" placeholder="Hora" v-model="hora" maxlength="5" :changed="change_hora" ></md-input>
                 -->

 </span>
</template>

<script>

import Datepicker from "./vuejs-datepicker/src/components/Datepicker";
import ptBR from "./vuejs-datepicker/src/locale/translations/pt-BR";

import Util from "../../library/Util";
import Mask from "../../library/Mask";

export default {
    components: {
            Datepicker
    },
    props: ['value', 'changed', 'id_hora', 'id_data'],
    model: {
            prop: 'value',
            event: 'selected'
    },
    watch: {

        value (val ) {
            //console.log("Data?? " + this.value );
            this.data = this.value;
            this.hora = Util.getHourFromDate(this.data);
        },
        hora(val){
            

                if (this.hora != null && this.hora.toString().length == 2 ){
                   this.hora = this.hora +":";
                }

                if (this.hora != null && this.hora.toString().length == 5 ){
                        //     this.setDataAlterada();
                } 
        },
        data(val){

                          //   this.setDataAlterada();
        }


        
    },
   data() {
                return {
                    data: null,
                    hora: "",
                    language_date: ptBR,
                    obj_mask: Mask,
                    pode_alterar: false,

                }
    },
    mounted(){
            this.pode_alterar = true;
    },
    methods: {
        setDataAlterada(){

            if ( ! this.pode_alterar ){
                return false;
            }

            if ( this.data == undefined ){
                return false;
            }

             if ( this.hora == undefined ){
                return false;
            }

                  if (this.hora != null && this.hora != "" && this.hora.length == 5 && this.data != null ){

                            var ar = this.hora.split(':');

                            var data = new Date( this.data.getFullYear(), this.data.getMonth(), this.data.getDate(),
                                            parseInt( ar[0]),parseInt( ar[1]));

                            this.$emit('selected', data);
                            return false;

                  }

                  if (this.data != null && this.hora == "" ){
                            this.$emit('selected', this.data);
                            return false;

                  }
                  if (this.data == null){
                      this.$emit('selected', this.data);
                      return false;
                  }
                    
        },
    
        datepicker_inputchanged(value){
              console.log("Mudou data"); console.log(value);  
            if ( this.changed != null ){
                this.changed(value);
            }

        },
        change_hora(event){
              console.log("Mudou hora"); console.log(event);  
        }
    }
      
}

</script>