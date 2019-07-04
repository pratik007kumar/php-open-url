<?php
function get_http_response_code($url) {
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}


if(get_http_response_code('http://somenotrealurl.com/notrealpage') != "200"){
    echo "error";
}else{
    file_get_contents('http://somenotrealurl.com/notrealpage');
}

?>
