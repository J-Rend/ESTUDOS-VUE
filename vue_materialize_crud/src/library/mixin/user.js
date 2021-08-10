export default {

    methods: {
       isLoggedUser: function () {
        
          if (   window.sessionStorage.getItem("rend_user") != null )
                 return true;

              return false;
       },
       setLastLogin: function (value){

                if ( window.sessionStorage.getItem("rend_login") != null ){
                       window.sessionStorage.removeItem("rend_login");
                }


               window.sessionStorage.setItem("rend_login", value );

       },
       getLastLogin: function (){
                if ( window.sessionStorage.getItem("rend_login") != null ){
                       return window.sessionStorage.getItem("rend_login");
                }

                return "";
       },
       setGlobalUser: function (obj){
               if ( window.sessionStorage.getItem("rend_user") != null ){
                       window.sessionStorage.removeItem("rend_user");
                }


               window.sessionStorage.setItem("rend_user", JSON.stringify(obj) );
               window.K_USER_TOKEN = obj.token;
       },

       setGlobalMenu: function (obj){


                if ( window.sessionStorage.getItem("rend_user_menu") != null ){
                       window.sessionStorage.removeItem("rend_user_menu");
                }


               window.sessionStorage.setItem("rend_user_menu", JSON.stringify(obj) );

       },

       getGlobalMenu: function (){
             var res =  window.sessionStorage.getItem("rend_user_menu");
             return JSON.parse(res);
       },
       
       getGlobalUser: function (){
             var res =  window.sessionStorage.getItem("rend_user");
             return JSON.parse(res);
       },
       getGlobalUserProp: function (prop){

              //  console.log("mixin chamado");
                
                var obj = this.getGlobalUser();
                
               // console.log( obj );
                //console.log( JSON.stringify(obj) );
               // console.log( Object.keys(   obj ) );

                  if ( obj == null )
                         return null;

                      return obj[prop];
       },
       removeGlobalUser: function(){

           window.sessionStorage.removeItem("rend_user");
           //window.obj_api.token_user = null;
       }
     }
}