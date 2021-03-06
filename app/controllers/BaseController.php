<?php

class BaseController extends Controller {

    public function __construct(){

    }

    protected function setupLayout(){

        if(!is_null($this->layout)):
            $this->layout = View::make($this->layout);
        endif;
    }

    public static function stringTranslite($string,$return_length = NULL){

        $rus = array("1","2","3","4","5","6","7","8","9","0","ё","й","ю","ь","ч","щ","ц","у","к","е","н","г","ш","з","х","ъ","ф","ы","в","а","п","р","о","л","д","ж","э","я","с","м","и","т","б","Ё","Й","Ю","Ч","Ь","Щ","Ц","У","К","Е","Н","Г","Ш","З","Х","Ъ","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","С","М","И","Т","Б"," ");
        $eng = array("1","2","3","4","5","6","7","8","9","0","yo","iy","yu","","ch","sh","c","u","k","e","n","g","sh","z","h","","f","y","v","a","p","r","o","l","d","j","е","ya","s","m","i","t","b","Yo","Iy","Yu","CH","","SH","C","U","K","E","N","G","SH","Z","H","","F","Y","V","A","P","R","O","L","D","J","E","YA","S","M","I","T","B","-");
        $string = str_replace($rus,$eng,trim($string));
        if(!empty($string)):
            $string = preg_replace('/[^a-z0-9-]/','',strtolower($string));
            $string = preg_replace('/[-]+/','-',$string);
            if (is_null($return_length)):
                return $string;
            elseif(is_numeric($return_length)):
                return Str::limit($string,$return_length,'');
            endif;

        else:
            return FALSE;
        endif;
    }

    public static function post_request($url){

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded",
                'content' => '',
                'timeout' => 60
            )
        );
        $context  = stream_context_create($opts);
        return file_get_contents($url, false, $context, -1, 40000);
    }
}