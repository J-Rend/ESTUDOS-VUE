<?php
namespace App\Http\Service;


use App\ArquivoCliente;

class UrlService{
    
    static function getUrl($id, $user, $tipo){
        
        $BASE_SISTEMA_PHP = env("BASE_SISTEMA_PHP");
        $BASE_SISTEMA_LARAVEL = env("BASE_SISTEMA_LARAVEL");
        
        $token = $user->id."-".md5("con.Tr.Ol". $user->id );
        
        $pagina = "inspecaoHTML.php";
        
        if ( $tipo == "relatorio"){
            
                $pagina = "relatorioHTML.php";
			
        }
        
        $url_imprimir = $BASE_SISTEMA_PHP .  $pagina . "?id=". $id."&token=".$token; 
        
        $url_pdf = $BASE_SISTEMA_PHP ."library/pdf_creator/index.php?" .
                                        "formato=pdf&url=" . urlencode($url_imprimir) .
                                        "&down=1&pref=painel&token=".$token;
        
        if ( $tipo == "inspecao"){
            
                 $url_imprimir = $BASE_SISTEMA_LARAVEL."/impauditoria". "?id=". $id."&token=".$token; 
                 $url_pdf = $BASE_SISTEMA_LARAVEL."/impauditoria". "?id=". $id."&token=".$token."&print=1"; 
        }
        
          if ( $tipo == "relatorio"){
            
                 $url_imprimir = $BASE_SISTEMA_LARAVEL."/imprelatorio". "?id=". $id."&token=".$token; 
                 $url_pdf = $BASE_SISTEMA_LARAVEL."/imprelatorio". "?id=". $id."&token=".$token."&print=1"; 
        }
        
        
        
        $url_email = $BASE_SISTEMA_PHP. "frame_email_json.php?token=".$token;
        
        $saida = array("print"=>$url_imprimir,"pdf"=>$url_pdf,"send"=>$url_email, "email"=> self::getEmail($id, $user, $tipo));
        
        if ( $tipo =="inspecao"){
           // $saida["grafico"] = $BASE_SISTEMA_LARAVEL."/impgrafico"; //."?id=". $id."&token=".$token."&print=1"; 
            $saida["grafico"] = $BASE_SISTEMA_PHP."mostraGrafico.php?id=".$id;
            //http://localhost:8080/julio/controllare_utf8/13506
        }
        
        return $saida;
    }
    
    static function getEmail($id, $user, $tipo ){
        $id_cliente = "";
        if ( $tipo == "relatorio"){
            $id_cliente = \App\Http\Dao\ConfigDao::executeScalar("select id_cliente as res from relatorio where id = ". $id );
        }
         if ( $tipo == "inspecao"){
            $id_cliente = \App\Http\Dao\ConfigDao::executeScalar("select id_cliente as res from inspecao where id = ". $id );
        }
          if ( $tipo == "arquivo_cliente"){
            $id_cliente = \App\Http\Dao\ConfigDao::executeScalar("select id_cliente as res from arquivo_cliente where id = ". $id );
        }
        
        if ( $id_cliente != ""){
            $email = \App\Http\Dao\ConfigDao::executeScalar("select email as res from cliente where id = ". $id_cliente );
            if ( $email == ""){
                $email = "";
            }
            return $email;
        }
        
        return "";
    }
    
}

