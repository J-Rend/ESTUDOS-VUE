<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Service\ErrorsService;

use Illuminate\Http\Request;
use App\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Dao\ImageDao;
use App\Http\Dao\PostsDao;

class BookController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	      $filtro = "";
              $order = " id "; $order_type = "desc";
              
              if ( $request->input( "filtro")  != ""){
                         	$str_filt = str_replace("'","''", $request->input( "filtro") );
                        	$filtro .= " and ( b.title like '%".$str_filt."%' or b.isbn like '%".$str_filt."%' or a.name like '%".$str_filt."%'  ) ";
               }

 		if ( $request->input( "order")  != ""){
			 $order = $request->input( "order");
		}
				
		if ( $request->input( "order_type")  != ""){
			 $order_type = $request->input( "order_type");
		}
               
                $sql = "select b.*, a.name as author_name from book b
                                    left join author a on a.id = b.author_id
                                    where 1 = 1 ". $filtro .
                                 " order by ".$order. " ".$order_type  ;
                 $itens = DB::select($sql);
				
		return $this->sendResponse( array("data" => $itens, "total"=>count($itens) ) );
				
	}
	
	/*
	            Route::get('/api/book', 'BookController@index');
                Route::get('/api/book/{id}', 'BookController@show');
                Route::put('/api/book/{id}', 'BookController@update');
                Route::post('/api/book', 'BookController@create');
                Route::delete('/api/book/{id}', 'BookController@destroy');
				
				Route::resource('book', 'BookController');
                router_resourceapi("book", "BookController");
				
				*/

        
		public function testheader(Request $request){

				  $o_auth_header  = $GLOBALS["auth_header"] ;
				  return array("msg"=>"Teste", "header" => $o_auth_header );
		}

        
	
	private function loadRequests(Request $request, \App\Book &$reg){

                $reg->title = $request->input('title');  
                $reg->description = $request->input('description');  
                $reg->isbn = $request->input('isbn');  
                $reg->stock = $request->input('stock');  
                $reg->author_id = $request->input('author_id');  
                
                $price_txt = $request->input('price_txt');
                $reg->price = \App\Http\Dao\ConfigDao::numeroBanco($price_txt); // $request->input('price');  
                $reg->editor = $request->input('editor');  
                
                
                $reg->date_release = \App\Http\Dao\ConfigDao::dataBanco($request->input('date_release_txt'). " ". $request->input('date_release_hour_txt') )  ; //$request->input('date_release');  
		
		
                \App\Http\Dao\ConfigDao::blankToNull(  $reg );

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
		$reg = new \App\Book;

		$this->loadRequests($request, $reg);
		
		$ret = $reg->save();

		$msg = "sucesso!"; $code = 1;
		if (! $ret  ){
                                $code = 0;
                                $msg = "erro";
		}

                   $this->format_show($reg);
         $final = array("msg"=>$msg, "code" =>  $code , "success" => $ret, 
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
		
		   $reg = Book::find($id);
                   $this->format_show($reg);
              

                   $final =  array( "code" =>  1, "data"=> $reg, "item"=> $reg);
		   return $this->sendResponse( $final  );
	}
        
        public function format_show(&$reg){
                   $reg->price_txt = \App\Http\Dao\ConfigDao::numeroTela($reg->price, 1);
                   $reg->date_release_txt = \App\Http\Dao\ConfigDao::DataBR($reg->date_release, true);
                   $reg->date_release_hour_txt = \App\Http\Dao\ConfigDao::DataToHourBR($reg->date_release);
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
		   $reg = Book::find($id);

		   $this->loadRequests($request, $reg);

			$ret = $reg->save();

		     $msg = "sucesso!"; $code = 1;
			if (! $ret  ){
                                                   $code = 0;
                                                    $msg = "erro";
			}
			
                   $this->format_show($reg);
           
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
		$reg = Book::find($id);
		$ret = $reg->delete();
		
		$final =  array("msg"=>"sucesso", "code" =>  1 , "success" => $ret, "data"=> $reg);
		
		return $this->sendResponse( $final  );
	}
        
        

}
