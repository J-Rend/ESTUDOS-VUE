import Util from './Util';

export default {


    newLine: function ( config )
    {
        return config.newline ?
            config.newline :
            navigator.userAgent.match(/Windows/) ?
                '\r\n' :
                '\n';
    },

    join: function (a, config){
        
        var boundary = config.fieldBoundary;
        var separator = config.fieldSeparator;

        if ( separator == undefined ){
            separator = ";";
        }

        var reBoundary = new RegExp(boundary, 'g');
        var escapeChar = config.escapeChar !== undefined ?
            config.escapeChar :
            '\\';

        var s = '';

        // If there is a field boundary, then we might need to escape it in
        // the source data
        for (var i = 0, ien = a.length; i < ien; i++) {
            if (i > 0) {
                s += separator;
            }

            s += boundary ?
                boundary + ('' + a[i]).replace(reBoundary, escapeChar + boundary) + boundary :
                a[i];
        }

        return s;

    },

    exportCSVFromDtTable: function (oldoutput, dt, config, cols_format) {

        //console.log("Export from csv? ", dt , config );
        var newLine = this.newLine(config);
        var data = config.buttons.exportData(config.exportOptions);
        var boundary = config.fieldBoundary;
        var separator = config.fieldSeparator;
        var reBoundary = new RegExp(boundary, 'g');
        var escapeChar = config.escapeChar !== undefined ? config.escapeChar : '\\';
      

        var header = config.header ? this.join(data.header, config) + newLine : '';
        var footer = config.footer && data.footer ? newLine + this.join(data.footer, config) : '';
        var body = [];

        for (var i = 0, ien = data.body.length; i < ien; i++) {


            if ( cols_format != null && cols_format != undefined ){

                for  ( var y = 0; y < cols_format.length; y++ ){
                    var format = cols_format[y];
                    var arr_item = data.body[i]; 
                    var col_data = arr_item[parseInt(format.index)];

                    if ( col_data == null  ){
                        col_data = "";
                    }

                    if ( format.format == "date" && col_data.indexOf(" ") ){

                        data.body[i][format.index] = col_data.substr(col_data.length - 10);

                    }
                }
                
            }
            
            body.push(this.join(data.body[i], config));
        }

        return header + body.join(newLine) + footer;
    }
}