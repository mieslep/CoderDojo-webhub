<?php 
$dir          = "ninja";

$return_array = array();

$preurlPieces = explode("/",$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

array_pop($preurlPieces);
$preurl=implode("/",$preurlPieces);
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https:' : 'http:';
$ninja_url = $protocol."//".$preurl."/ninja/";


if(is_dir($dir)){

    if($dh = opendir($dir)){
        while(($file = readdir($dh)) != false){
        	if (is_dir($dir."/".$file) and $file != "." and $file != "..") {
                $return_array[] = array($file => $ninja_url.$file); 
            }
        }
    }

	header('Content-type: application/json');
    echo json_encode($return_array);
}

?>


