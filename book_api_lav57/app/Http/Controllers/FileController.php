<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Service\ErrorsService;

use Illuminate\Http\Request;
use App\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Dao\ImageDao;
use App\Http\Dao\FileDao;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Service\Imagem\FactoryServiceImagem;

class FileController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
		public function index(Request $request)
                {

                                         $id_tabela = $request->input("id_tabela");
                                         $id_registro = $request->input("id_registro");

                                        $itens = \App\Http\Dao\FileDao::getList($id_tabela, $id_registro);

                                        return $this->sendResponse( $itens );
                }
	
        
		public function testheader(Request $request){

				  $o_auth_header  = $GLOBALS["auth_header"] ;
				  return array("msg"=>"Teste", "header" => $o_auth_header );
		}

        
	
	private function loadRequests(Request $request, \App\File &$reg){

            $reg->name = $request->input('name');  
            $reg->type = $request->input('type');  
            $reg->table = $request->input('table');  
            $reg->title = $request->input('title');  
            $reg->size = $request->input('size');  
            $reg->id_registry = $request->input('id_registry');  
            $reg->old_name = $request->input('old_name');  
            $reg->thumbs = $request->input('thumbs');  
            $reg->pid = $request->input('pid');  
		
		
         PostsDao::blankToNull(  $reg );

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		
	
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$reg = new \App\File;

		$this->loadRequests($request, $reg);
		
		$ret = $reg->save();

		$msg = "sucesso!"; $code = 1;
		if (! $ret  ){
                                                $code = 0;
                                                $msg = "erro";
		}

                        $final = array("msg"=>$msg, "code" =>  $code , "success" => $ret, "results"=> $reg,
                       "data"=> $reg, "item"=> $reg);
					   
		return $this->sendResponse( $final  );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
		   $reg = File::find($id);

          
           $final =  array( "code" =>  1, "data"=> $reg, "item"=> $reg);
		   return $this->sendResponse( $final  );
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id, Request $request)
	{
		// return "metodo EDIT";
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		   $reg = File::find($id);

		   $this->loadRequests($request, $reg);

			$ret = $reg->save();

		     $msg = "sucesso!"; $code = 1;
			if (! $ret  ){
                  $code = 0;
	              $msg = "erro";
			}
			
           
            $final = array("msg"=>$msg, "code" =>  $code , "success" => $ret, "data"=> $reg, "item" => $reg);
		    return $this->sendResponse( $final  );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$reg = File::find($id);
                
                FileDao::removeFile( $reg );
                
                
		$ret = $reg->delete();
		
		$final =  array("msg"=>"sucesso", "code" =>  1 , "success" => $ret, "data"=> $reg);
		
		return $this->sendResponse( $final  );
	}
        
        
        
        
           public function upload(Request $request)
                {

                      $post = $request->all();
                      //$post =  $request->all();
                       $ano = date("Y"); $mes = date("m");
                       
                       $zero_file = \Request::file('file0' );
                       
                       if (is_null($zero_file))
                       {
                           return $this->sendError("file0 esta vazio", 400);
                       }

                      $file_qtde =   $request->input("file_qtde"); //$post[ "file_qtde"] ;//
                      $author_id =  $request->input("author_id"); //@$post[ "author_id"] ;//
                      $parent_id =  $request->input("parent_id"); //$post[ "parent_id"] ; //
                      $type_image = $request->input("type_image"); //$post[ "type_image"] ; // 
                      $tipo =    $request->input("tipo"); //  $post[ "tipo"];

                      if ( $type_image == "")
                          $type_image = "midia";

                      $objUpload = $this->getObjUpload();
                      
                      $pasta = \App\Http\Dao\FileDao::getFolderByTabela($tipo);
                      
                      $this->garantePasta($pasta, $parent_id );

                      $saida = array();
                      $saida_response = array();
                      $ids = "0 ";

                      for ( $i = 0; $i < $file_qtde; $i++ ){

                      $file = \Request::file('file'. $i); // retorna o objeto em questão

                      $arr_metadados = array();
                      
                                    $reg = new File();
                                    
                                    $metadados = $this->getArrayImagem(); 
                                    
                                    
                                    $mime_type = $file->getMimeType();
                                    
                                    $sizes = null;
                                    
                                    if (strpos(" ". $mime_type,"image") ){

                                                $width = 0;  $height = 0;
                                                list($width, $height) = getimagesize(  $file->getPathName() );

                                                $metadados["width"]  = $width;
                                                $metadados["height"] = $height;
                                                $metadados["size"] = $file->getClientSize();
                                                $metadados["original_file"] = $file->getClientOriginalName();
                                                
                                               $sizes = $this->generateThumbs($file, $pasta, $parent_id, $this->trataNomeImagem( $file->getClientOriginalName() ), 
                                                                       $file->getClientOriginalExtension(),
                                                                         $file->getMimeType() );
                                    
                                    }

                                    if ( $parent_id != ""){
                                         $reg->id_registry = $parent_id;
                                    }

                                    $reg->type = $mime_type;
                                    $reg->table  = $tipo;
                                    $reg->size  = $file->getClientSize();
                                    
                                    
                                    $imageFileName = $this->trataNomeImagem( $file->getClientOriginalName() ) . '.' . $file->getClientOriginalExtension();
                                    $reg->name =  $imageFileName;

                                  
                                    if ( !is_null($sizes)){
                                        
                                            $metadados["sizes"] = $sizes;
                                    }
                                    $metadados["file"] = $imageFileName;

                                    $reg->thumbs = json_encode($metadados);
                                    if ($reg->save()) {

                                         $reg->save();
                                    }
                                    
                                   $filePath =  $imageFileName; //env("S3_BUCKET") .

                                   $objUpload->sendImagem($file , $pasta."/".$parent_id."/". $filePath );


                                    $ids .= ",". $reg->id;
                                    $saida[ count( $saida)] = $reg;

                                    //$saida_response[ count( $saida_response)] = $response;

                                }

                                $ls = \App\Http\Dao\FileDao::getList($tipo, $parent_id);
                                        
                                        //$this->getList(" and id in ( ".$ids.") " );

                                return $ls;

                                //return $saida;

                }
                
              
                var $objUpload;

               function getObjUpload(){

                    if ( $this->objUpload == null ){

                           $this->objUpload = FactoryServiceImagem::create("path");
                    }

                    return $this->objUpload;
                }
                
               public function getArrayImagem(){

                        $arr = array("width"=>"",
                                      "height"=>"",
                                      "file"=>"",
                                      "size"=>"",
                                      "sizes"=>"",

                            );

                        return $arr;

                    }
                    
                function garantePasta($pasta, $id_registro){
                                     if ( !is_dir(env("PATH_ANEXO") . DIRECTORY_SEPARATOR . $pasta )){
                                         mkdir(env("PATH_ANEXO") . DIRECTORY_SEPARATOR . $pasta );
                                     }
                                     if ( !is_dir(env("PATH_ANEXO") . DIRECTORY_SEPARATOR . $pasta . DIRECTORY_SEPARATOR . $id_registro )){
                                         mkdir(env("PATH_ANEXO") . DIRECTORY_SEPARATOR . $pasta  . DIRECTORY_SEPARATOR . $id_registro  );
                                     }  
                }


                function generateThumbs($file, $pasta, $id_registro, $imageFileName, $extension, $mime_type  ){
                    
                    
                                    $saida = array();

                                     $objUpload = $this->getObjUpload();

                                     $final_path = env("PATH_ANEXO") . DIRECTORY_SEPARATOR . $pasta . DIRECTORY_SEPARATOR. $id_registro;
                                     
                                     $data = $this->getArrayImagem();

                                     $thumb_name  = $imageFileName."-150x150.".$extension;
                                     $thumbnail_file = Image::make( $file );
                                     
                                     try{
                                         if (extension_loaded("exif")){
                                                 $thumbnail_file->orientate();
                                         }
                                     } catch (Exception $ex) {

                                     }
                                     
                                     
                                     $thumbnail_file->resize('150','150');
                                     $thumbnail_file->save($final_path. DIRECTORY_SEPARATOR .$thumb_name );

                                  
                                     $data["file"] =  $thumb_name;
                                     $data["width"] = 150;
                                     $data["height"] = 150;
                                     $data["mime-type"] = $mime_type;
                                     
                                     $saida["thumbnail"] = $data;
                                     return $saida;

                }
                
                function trataNomeImagem($img){

                    $final = str_replace(" ","", $img);
                    $final = strtolower($final);

                    $semext = explode(".", $final) ;
                    $semext = $semext[0];
                    $saida = $semext;
                    
                    
                    $saida = str_replace("(","", $saida);
                    $saida = str_replace(")","", $saida);

                    $saida  = \App\Http\Service\UtilService::removeAcentos( $saida );
                    $saida  = \App\Http\Service\UtilService::fileNameClean( $saida );

                    if ( strlen($saida) > 100 ){
                         $saida = substr($saida, 0, 100);
                    }
                    return $saida;
                } 
        

}
