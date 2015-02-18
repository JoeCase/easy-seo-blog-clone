<?php
    require_once 'actions/functions.php';

    $arr = array();

    $href = $_SERVER['SERVER_NAME'];
    if (isset($_REQUEST['section-url'])) {
        $href  = str_replace("%2F", "/", $_REQUEST['section-url']);
        $href  = str_replace("%3A", ":", $href);
        $href  = str_replace("%23", "#", $href);
    }
    if(substr($href, strlen($href)-1) == "?"){
        $href = substr($href, 0, strlen($href)-1);
    }
    if(substr($href, strlen($href)-1) != "/"){
        $href = $href."/";
    }
    $GLOBALS['section-url'] = $href;
    $request  = str_replace("//", "/", $_SERVER['REQUEST_URI']);

    #split the path by '/'
    $arr = explode("?", $request);


    // remove parent directories used in SCRIPT_NAME from query path
    $script_name = str_replace("//", "/", $_SERVER['SCRIPT_NAME']);
    $root_path = str_replace("//", "/", root_path());
    $path = preg_replace(array('/'.preg_quote($script_name, '/').'/', '/'.preg_quote($root_path, '/').'/' ), array( '', '' ), $arr[0], 1);
    $params = array_values(array_filter(explode("/", $path))); // remove empty string from array (array_filter) and index array items from 0 (array_values)
    //$params = array_filter(explode("/", $path)); // remove empty string from array (array_filter) and index array items from 0 (array_values)
    //var_dump($params);

    $GLOBALS['url'] = array();
    if(sizeof($params) == 1){
        if($params[0] != ""){
            $GLOBALS['url']['slug'] = str_replace("%","",urlencode(urldecode($params[0])));
            $GLOBALS['url_path'] = $arr[0];
        }
    } else {
        for ($i = 0; $i < sizeOf($params); $i+=2) {
            if ($params[$i] == 'page') {
                $GLOBALS['current_page'] = $params[$i+1];
            }
            else {
                $GLOBALS['url'][$params[$i]] = $params[$i+1];
            }
        }
    }
    $GLOBALS['url']['published'] = 1;

    //Firefox fix
    if (isset($GLOBALS['url']['slug']) && strpos($href,$GLOBALS['url']['slug']) !== false) {
        $GLOBALS['section-url'] = substr($href, 0, strpos($href,$GLOBALS['url']['slug']));
    }
    // set default
    if (!isset($GLOBALS['current_page'])) {
        $GLOBALS['current_page'] = 1;
    }
?>