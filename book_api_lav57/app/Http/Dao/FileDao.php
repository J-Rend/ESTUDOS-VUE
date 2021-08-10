<?php
namespace App\Http\Dao;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Dao\ConfigDao;

use App\Arquivo;

class FileDao {
    
       public static function getList($id_tabela, $id_registro){
           $caption = "";
           
             $sql = "select p.*, '' as url, '' as url_thumb from file p"
                     . "  where 1 = 1 and p.table='".$id_tabela."' and p.id_registry= " . $id_registro;
         
             
             $sql .= " order by type desc, id asc ";
              
             
             $itens = DB::select($sql);
             
             $process = env("BASE_THUMB_PROCESS");
             

             for ($i=0; $i < count( $itens) ; $i++) { 
                    $item = &$itens[$i];
                    
                    $thumbs = $item->thumbs;
                    
                    if ( strpos(" ". $item->type, "image") > 0 ){
                            if ( trim($thumbs) != ""){

                                $ar = json_decode($thumbs);
                                
                                if (property_exists($ar, "sizes") && property_exists($ar->sizes, "thumbnail") && 
                                         property_exists($ar->sizes->thumbnail, "file") ){
                                    
                                     $item->url_thumb = self::getURL($item,   $ar->sizes->thumbnail->file );
                                }

                            } else {


                                $item->url_thumb = self::getURL($item,   $item->name);
                            }
                            
                            if ( is_null($item->url_thumb) || $item->url_thumb == "" ){
                                
                                $item->url_thumb = self::getURL($item,   $item->name);
                            }
                    }
                    $item->url = self::getURL($item,$item->name);
                    
                   // $item->data = $this->DataBR($valor, true); //Colocando como formato BR
             }
             
             return $itens;
           
       }
       
       
       public static function base64ToImage($base64_string, $output_file) {
                        $file = fopen($output_file, "wb");

                        $data = explode(',', $base64_string);

                        fwrite($file, base64_decode($data[1]));
                        fclose($file);

                        return $output_file;
       }
       
       public static function getFolderByTabela($tabela){
           return $tabela;
       }
       
       public static function garantePasta($pasta, $id_registro){
                                     if ( !is_dir(env("PATH_ANEXO") . DIRECTORY_SEPARATOR . $pasta )){
                                         mkdir(env("PATH_ANEXO") . DIRECTORY_SEPARATOR . $pasta );
                                     }
                                     if ( !is_dir(env("PATH_ANEXO") . DIRECTORY_SEPARATOR . $pasta . DIRECTORY_SEPARATOR . $id_registro )){
                                         mkdir(env("PATH_ANEXO") . DIRECTORY_SEPARATOR . $pasta  . DIRECTORY_SEPARATOR . $id_registro  );
                                     }  
                                     
                                     
                                     return env("PATH_ANEXO") . DIRECTORY_SEPARATOR . $pasta  . DIRECTORY_SEPARATOR . $id_registro;
       }
       
       
       public static function getURL( $item,   $arquivo){
           
            $base_path = env("BASE_URL_ANEXO");
            
            $pasta = $item->table;
            
            return $base_path."/".$pasta."/".$item->id_registry."/".$arquivo;
           
       }
       
       public static function removeFile($item){
           
           $folder = env("PATH_ANEXO") . DIRECTORY_SEPARATOR . $item->table  . DIRECTORY_SEPARATOR . $item->id_registry;
           
           if (file_exists($folder . DIRECTORY_SEPARATOR . $item->name)){
               unlink($folder . DIRECTORY_SEPARATOR . $item->name);               
           }
       }
       
       
       
        
}

