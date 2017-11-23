<?php

    function DirectoryToArray($directory) {
        
        $items = array();
        
        $scan = array_slice(scandir($directory), 2);
        
        for($i = 0; $i < count($scan); $i++){
                        
            if(is_dir($directory.'/'.$scan[$i])){
                
                $search = DirectoryToArray($directory.'/'.$scan[$i]);
                
                for($e = 0; $e < count($search); $e++){
                    
                    if(is_dir($search[$e]['directory'].'/'.$search[$e]['name'])){
                        
                        array_push($items, array('type' => 'FOLDER', 'directory' => $search[$e]['directory'], 'name' => $search[$e]['name'], 'identifier' => md5($search[$e]['directory'].'/'.$search[$e]['name'])));
                        
                    }else{
                        
                        array_push($items, array('type' => 'FILE', 'directory' => $search[$e]['directory'], 'name' => $search[$e]['name'], 'identifier' => md5($search[$e]['directory'].'/'.$search[$e]['name'])));
                        
                    }
                    
                }
                
                array_push($items, array('type' => 'FOLDER', 'directory' => $directory, 'name' => $scan[$i], 'identifier' => md5($directory.'/'.$scan[$i])));
            
            }else{
                
                array_push($items, array('type' => 'FILE', 'directory' => $directory, 'name' => $scan[$i], 'identifier' => md5($directory.'/'.$scan[$i])));
                
            }
            
        }
        
        return $items;
        
    }
    
?>
