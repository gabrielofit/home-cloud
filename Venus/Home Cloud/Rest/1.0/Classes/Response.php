<?php 

class Response{
    
    public function Print($status, $code, $description, $data = array()){
        
        http_response_code(200);
        header("Content-type:application/json");
        
        $header = array('status' => intval($status),
            'code' => $code,
            'description' => $description);
        $payload = $data;
        
        $responseAsArray = array('header' => $header,
            'payload' => $payload
        );
        
        $responseAsJson = json_encode($responseAsArray, JSON_PRETTY_PRINT);
        echo $responseAsJson;
        exit;
        
    }
    
}

?>