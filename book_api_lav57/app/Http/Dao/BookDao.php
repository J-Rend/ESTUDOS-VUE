<?php
namespace App\Http\Dao;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Dao\ConfigDao;

use App\Book;

class BookDao {
    
       public static function getListGridCad($filtro, $order, $order_type){
           
             $sql = "select p.* from book p where 1 = 1 ". $filtro .
                     " order by ".$order. " ".$order_type;
             
             $itens = DB::select($sql);
             

             for ($i=0; $i < count( $itens) ; $i++) { 
                    $item = &$itens[$i];
                    $valor = $item->data;
                    
                    
                   // $item->data = $this->DataBR($valor, true); //Colocando como formato BR
             }
             
             return $itens;
           
       }
    
       public static function salvarDadosJson( $hd_json, $ids_delete_json ){
           
           $itens = json_decode($hd_json);
           $ids_delete = json_decode($ids_delete_json);
           
           $qtde_salvo = 0; $qtde_delete = 0;
           
           for ( $i = 0; $i < count($itens); $i++){
                           
                       $item_req = $itens[$i];    
                       
                       $reg = new Book();
                       
                       if ( $item_req->id != ""){
                             $reg = Book::find($item_req->id);
                       }
					            $reg->title = $item_req->title;   
         $reg->description = $item_req->description;   
         $reg->isbn = $item_req->isbn;   
   $reg->stock = ConfigDao::numeroBanco(  $item_req->stock  );  
   $reg->author_id = ConfigDao::numeroBanco(  $item_req->author_id  );  
   $reg->price = ConfigDao::numeroBanco(  $item_req->price  );  
         $reg->editor = $item_req->editor;   
   $reg->date_release = ConfigDao::dataBanco(  $item_req->date_release  );  
                       
                       
                       ConfigDao::blankToNull($reg);
                       
		       $ret = $reg->save();
                       $qtde_salvo++;
           }
           
           for ( $i = 0; $i < count($ids_delete); $i++){
               $item_req = $ids_delete[$i];
               
                 if ( $item_req != ""){
                             $reg = Book::find($item_req);
                             $reg->delete();//Removendo o item desejado para deletar.
                             $qtde_delete++;
                 }
           }
           
           return array("qtde_salvo" => $qtde_salvo, "qtde_deleted" => $qtde_delete , "success"=> true );
           
       }
       
       
        
}

