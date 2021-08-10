import { min } from "moment";

export default {

    methods: {

          dateToBR(value){

                     if (value != null && value.indexOf("-") >-1 ){

                                var ar = value.split('-');

                                //2019-11-31

                                return ar[2]+"/"+ar[1]+"/"+ar[0];
                     }


                     return value;
          },
          dateToUs(value){

                 if (value != null && value.indexOf("/") >-1 ){

                                var ar = value.split('/');   //16/11/1986

                                //2019-11-31

                                return ar[2]+"-"+ar[1]+"-"+ar[0];
                     }


                     return value;

          },
          numberToPhone(number){

              var numbers = number.replace(/\D/g, '');
              //(47-97777-98888)
              var char = { 0: '(', 2: ')', 6: '-' };
              var saida = "";
                         //  obj.value = '';
                           for (var i = 0; i < numbers.length; i++) {
                               saida += (char[i] || '') + numbers[i];
                           }

                           return saida;

          },
          
          getOnlyNumber(txt){

              var numb = txt.match(/\d/g);
           var numb2 = numb.join("");

           return numb2; 

          },

          getApiDateInt(date){
              if ( date == null )
                 return -1;

              var ar = date.split(' ');
              var date1 = ar[0].split('-');
              //ano - mes - dia

              return Date.parse( date1[1] + " " + date1[2]+" " + date1[0]);


          },

          getApiDateHour(date){
            if ( date == null )
               return -1;

               //2019-05-17 10:10:00

            var ar = date.split(' ');
            var date1 = ar[0].split('-');
            //ano - mes - dia

            var ar_sub_hora = ar[1].split(":");

            var hora = ar_sub_hora[0];
            var minuto = ar_sub_hora[1];
            var segundo = ar_sub_hora[2];

            if ( segundo == null ){
                segundo = 0;
            }

            if ( minuto == null ){
                minuto = 0;
            }

            if ( hora == null ){
                hora= 0;
            }

            var arSubb = new Date( parseInt(date1[0]), parseInt(date1[1]) -1, parseInt(date1[2]),
                       parseInt(hora), parseInt(minuto), parseInt(segundo), 0);

            return arSubb;
           // return Date.parse( date1[1] + " " + date1[2]+" " + date1[0]+" "+ar[1]);


        },


    }
}