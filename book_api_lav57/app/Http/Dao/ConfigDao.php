<?php
namespace App\Http\Dao;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Config;


class ConfigDao {
    
    
    public static function executeScalar( $sql ){
					$ar = DB::select($sql);
                                        
                                        if ( count($ar) <= 0 )
                                            return "";
					
					//print_r( $ar ); die(" ");
					
					return $ar[0]->res;
    }
    
        public static function executeScalar2( $sql ){
					$ar = DB::connection('mysql_midiaclip')->select($sql);
                                        
                                        if ( count($ar) <= 0 )
                                            return "";
					
					//print_r( $ar ); die(" ");
					
					return $ar[0]->res;
    }
    
         
    
      public static function blankToNull(&$eloquentObj){
                       $columns =  $eloquentObj->getFillable();
                    
                    
                        foreach ($columns as $column)
                        {
                              if ( $eloquentObj->$column == "" ){
                                  $eloquentObj->$column = null;
                              }
                        }
              }
    
    
         public static function dataBanco($valor)
	{
        if(is_null($valor)){return "";}
        if(trim($valor) == ""){return "";}
		if ($valor == "")

			return "";
		
                $hora = "";
                if ( strpos(" ". $valor, ":")){
                    $ar = explode(" ", $valor);
                    
                    if ( @$ar[1] != ""){
                          $hora = " ". $ar[1];
                    }
                    $valor = $ar[0];
                }
                
                
		$arr = explode("/",$valor);
		
		return trim($arr[2])."-".trim($arr[1])."-".trim($arr[0]). $hora;
		
	}
        
      public static function DataBR($valor, $semhora =false)
            {

                if ($valor == "")
                    return "";

                if ( strpos(" ". $valor,".")){
                    $exp = explode(".", $valor);
                    $valor = $exp[0];
                }


                $valor = str_replace("-","/",$valor);

                $ar = explode(" ",$valor);
                $arr = explode("/",$ar[0]);

                if ( count($arr) < 3)
                    return "";

                $hora = "";
                if (! $semhora)
                    $hora = " " . @$ar[1];

                return $arr[2]."/".$arr[1]."/".$arr[0].$hora;

            }
            
              public static function DataToHourBR($valor)
            {

                if ($valor == "")
                    return "";

                if ( strpos(" ". $valor,".")){
                    $exp = explode(".", $valor);
                    $valor = $exp[0];
                }


                $valor = str_replace("-","/",$valor);

                $ar = explode(" ",$valor);
                $arr = explode("/",$ar[0]);

                if ( count($arr) < 3)
                    return "";

                $hora = "";
                    $hora =  @$ar[1];

                return $hora;

            }
            
         public static  function numeroBanco($valor)
	{
		$val = str_replace(".","",$valor);
		$val = str_replace(",",".",$val);
		
		$val  = round($val, 2 );
		
		$val = str_replace(".00","",$val);
		for ( $i =1 ; $i <= 9; $i++)
		{
			$val = str_replace(".".$i."0",".".$i,$val);
			
		}
		
		return $val;
	}
	public static  function numeroTela($valor, $removeZeros=1)
	{
		if ($valor == null || $valor =="")
			return "";
		
		$val = number_format($valor,2,",",".");
		
		// $val = str_replace(".",",",$valor);
		if ( $removeZeros )
		{
			$val = str_replace(",00","",$val);
			for ( $i =1 ; $i <= 9; $i++)
			{
				$val = str_replace(",".$i."0",",".$i,$val);
				
			}
			
		}
		if ( $removeZeros == 1 )
			$val = str_replace(".","",$val);
			
			
		return $val;
	}
        
              
	
	public static function arrayToString($arr, $propriedade, $sep = ",", 
                 $idd = 0, $testaVazio = false, $format = false)
	{
		$str = "";
		for ($i = 0; $i< count($arr); $i++)
		{
			$vv = $arr[$i]->$propriedade;
			
			if ( $testaVazio ){
				if ( trim($vv) == "" )
					continue;	
			}
			
			if ( $format ){
				$vv = str_replace("'","",	$vv);
				$vv = str_replace($sep,"+",	$vv);
			}
                        
                        if ( $str != "")
                            $str .= $sep;
                        
                        $str .= $vv;	
		}	
		
		return $str;
		
	}
    
    
}
