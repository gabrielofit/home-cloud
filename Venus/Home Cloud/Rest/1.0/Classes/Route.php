<?php

class Route{
    
    private $GET;
    private $POST;
    private $PUT;
    private $PATCH;
    private $DELETE;
    private $OTHER;
    
    public function Define($method, $callback){
        
        if(strtoupper($method) == 'GET'){
            $this -> GET = $callback;
            return;
        }
        
        if(strtoupper($method) == 'POST'){
            $this -> POST = $callback;
            return;
        }
        
        if(strtoupper($method) == 'PUT'){
            $this -> PUT = $callback;
            return;
        }
        
        if(strtoupper($method) == 'PATCH'){
            $this -> PATCH = $callback;
            return;
        }
        
        if(strtoupper($method) == 'DELETE'){
            $this -> DELETE = $callback;
            return;
        }
        
        if(strtoupper($method) == 'OTHER'){
            $this -> OTHER = $callback;
            return;
        }
    }
    
    public function Run(){
        
        if(isset($_SERVER['REQUEST_METHOD'])){
            
            $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
            
            if($requestMethod == 'GET'){
                if(function_exists($this -> GET)){
                    call_user_func($this -> GET);
                }
            }
            
            if($requestMethod == 'POST'){
                if(function_exists($this -> POST)){
                    call_user_func($this -> POST);
                }
            }
            
            if($requestMethod == 'PUT'){
                if(function_exists($this -> PUT)){
                    call_user_func($this -> PUT);
                }
            }
            
            if($requestMethod == 'PATCH'){
                if(function_exists($this -> PATCH)){
                    call_user_func($this -> PATCH);
                }
            }
            
            if($requestMethod == 'DELETE'){
                if(function_exists($this -> DELETE)){
                    call_user_func($this -> DELETE);
                }
            }
            
            if($requestMethod != 'GET' && 
               $requestMethod != 'POST' && 
               $requestMethod != 'PUT' && 
               $requestMethod != 'PATCH' && 
               $requestMethod != 'DELETE'){
                if(function_exists($this -> OTHER)){
                    call_user_func($this -> OTHER);
                }
            }
            
        }
        
    }
    
}

?>