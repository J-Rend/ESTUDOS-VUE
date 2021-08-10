<template>
 <div >
        <span v-for="(arquivo, index) in arquivos" :key="arquivo.id" >
                     <a  class="item-img"  v-if="arquivo.type.indexOf('image') > -1"
                       v-bind:href="arquivo.url"  target="_blank"
                     >
                      <img class="img-responsive" v-bind:src="arquivo.url_thumb" v-bind:alt="caption"  >
                     
                    </a> 
                     <a v-else v-bind:href="arquivo.url" class="item-img"  target="_blank" >
                         <md-icon style="font-size: 30px">attachment</md-icon>
                         <br>
                         {{arquivo.arquivo}}</a>
                   
         </span>            



                    <vue-easy-lightbox
                            :visible="visible"
                            :imgs="imgs"
                            @hide="handleHide"
                        ></vue-easy-lightbox>

 </div>                    
</template>
<script>
import VueEasyLightbox from 'vue-easy-lightbox';
// @click="() => showImg(index)"
export default {
  components: {
      VueEasyLightbox
  }, 
  data() {
    return {
      imgs: [],
      visible: false,
      index: 0   // default
    }
  },
  mixins: [ ],
  mounted(){

         this.imgs = this.arquivos.map(function (item){
           if ( item.type.indexOf("image") > -1 ){
             return item.url
           }  
         }) 
         
         //this.getFields(  this.arquivos, "url");        
           console.log("imsg?"); console.log( this.imgs );

  },
  props: ['arquivos', 'caption'],
     methods: {
     
      getFields(input, field) {
            var output = [];
            for (var i=0; i < input.length ; ++i)
                output.push(input[i][field]);
            return output;
        },
      showImg (index) {
        this.index = index
        this.visible = true
      },
      handleHide () {
        this.visible = false
      }
    }
}
</script>