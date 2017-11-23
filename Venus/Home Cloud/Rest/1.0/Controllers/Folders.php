<?php

// Require all necessary classes
require_once '../Classes/Route.php';
require_once '../Classes/Configurations.php';
require_once '../Classes/Response.php';


// Require all necessary external classes
require_once '../Classes/Externals/DirectoryToArray.php';


// Require All necessary definitions
require_once '../Definitions/Input.php';
require_once '../Definitions/Database.php';


// Set some configurations for php.ini
$configurations = new Configurations();
$configurations -> Setup();


// Set some routes using HTTP verbs
$route = new Route();
$route -> Define('GET', 'OnGET');
$route -> Define('POST', 'OnPOST');
$route -> Run();


// Routes setted for HTTP verbs
function OnGET(){
    
    $response = new Response();
    $payload = json_decode('{"root":{"name":"gabrielofit","identifier":"3748b11cdab87a69d6777c77e01170e7"},"folders":[{"name":"Documentos","directory":"/gabrielofit","identifier":"1e14eed3d9ac0dae4a31278f4c740af6"},{"name":"Desenvolvimento Web","directory":"/gabrielofit/Documentos","identifier":"670de985322a776e748bd957c1ca4bba"}]}');
    $response -> Print(200, 'FOLGET0', 'Folders got', $payload);
    
    
    
    if(isset($_GET['token']) == false){
        die('token not set');
    }
    if(isset($_GET['directory']) == false){
        die('directory not set');
    }
    
    
    if(empty($_GET['token'])){
        die('token is empty');
    }
    if(empty($_GET['directory'])){
        die('directory is empty');
    }
    
    
    if(preg_match(REGEX_FOR_TOKEN, $_GET['token']) == false){
        die('token is invalid');
    }
    
    if(preg_match(REGEX_FOR_DIRECTORY, $_GET['directory']) == false){
        die('directory is invalid');
    }
    
    
    $token = $_GET['token'];
    $directory = $_GET['directory'];
    
    
    if($directory == '/'){
        $directory = '';
    }
    
    
    // Establishes a connection with database
    $connection = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD);
    if(!$connection){
        die('cant establishes a connection with database, try again');
    }
    
    $selection = mysqli_select_db($connection, DATABASE_NAME);
    if(!$selection){
        die('cant select database in current connection');
    }
    
    
    // Check token
    $query0 = mysqli_query($connection, "SELECT `owner`, `token`, `created at`, `expires at`, `is valid` FROM `tokens` WHERE `token` = '$token'");
    if(!$query0){
        die('cant query database in query0, sorry.');
    }
    
    if($query0 -> num_rows == 0){
        die('token doesnt exists');
    }
    
    $query0 = mysqli_fetch_array($query0);
    
    if(boolval($query0['is valid']) == false){
        die('this token is no more valid');
    }
    
    if(date('Y-m-d H:i:s', strtotime('+0 days')) > date('Y-m-d H:i:s', strtotime($query0['expires at']))){
        
        $query1 = mysqli_query($connection, "UPDATE `tokens` SET `is valid`='0'");
        if(!$query1){
            die('cannot update the token validation');
        }
        
        
        die('this token has been expired');
    }
    
    
    // Get cloud identifier
    $query1 = mysqli_query($connection, "SELECT `identifier` FROM `clouds` WHERE `owner` = '".$query0['owner']."'");
    if(!$query1){
        die('cant query database in query0, sorry.');
    }
    
    $query1 = mysqli_fetch_array($query1);
    
    
    // IO actions
    //
    // WARNINGS
    //
    // The sent directory ($directory) ever starts with '/' and ever ends without '/'
    //
    if(file_exists('../../../Data/Clouds/'.$query1['identifier'].$directory)){
        if(is_dir('../../../Data/Clouds/'.$query1['identifier'].$directory) == false){
            die('can not get a file using folders.php, please use files.php scritp');
        }
    }else{
        die('directory doesnt exists');
    }

    $directoryAsArray = DirectoryToArray($_SERVER['DOCUMENT_ROOT'].'/Venus/Cloud/Data/Clouds/'.$query1['identifier'].$directory);
    
    $response -> Print(200, 'FOLGET0', 'Folders got', $directoryAsArray);
        
    /*
     * 
     *     STOPED HERE
     *     STOPED HERE
     *     STOPED HERE
     *     STOPED HERE
     *     STOPED HERE
     *     STOPED HERE
     *     STOPED HERE
     *     
     *     
     *     Agora que o mapeamente de diretório está funcionando, eu preciso montar um script que monte uma resposta padrão
     *     de retorno para o browser. Preciso montar também uma api em JavaScrip para melhorar o desenvolvimento do 
     *     front-end
     *     
     *     
     *     STOPED HERE
     *     STOPED HERE
     *     STOPED HERE
     *     STOPED HERE
     *     STOPED HERE
     *     STOPED HERE
     *     STOPED HERE
     *     STOPED HERE
     *     STOPED HERE
     *     
    */
    
}
function OnPOST(){

    
    $token = null;
    $directory = null;
    
    $connection = null;
    $selection = null;
    
    $select = null;
    $insert = null;
    
    $response = new Response();
    
    
    if(!isset($_POST['token']))
        $response -> Print('400', 'FOLPOS1', 'A token was not set');
    if(!isset($_POST['directory']))
        $response -> Print('400', 'FOLPOS2', 'A directory was not set');
    
    if(!preg_match(REGEX_FOR_TOKEN, $_POST['token']))
        $response -> Print('400', 'FOLPOS3', 'Sent token has a invalid format');
    if(!preg_match(REGEX_FOR_DIRECTORY, $_POST['directory']))
        $response -> Print('400', 'FOLPOS4', 'Sent directory has a invalid format');
    
        
    $token = $_POST['token'];
    $directory = $_POST['directory'];
    
    
    die();
    
    
    // Set sent data into variables
    $token = 
    $directory = $_POST['directory'];
    
    
    // Establishes a connection with database
    $connection = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD);
    if(!$connection){
        die('cant establishes a connection with database, try again');
    }
    
    $selection = mysqli_select_db($connection, DATABASE_NAME);
    if(!$selection){
        die('cant select database in current connection');
    }
    
    
    // Check token
    $query0 = mysqli_query($connection, "SELECT `owner`, `token`, `created at`, `expires at`, `is valid` FROM `tokens` WHERE `token` = '$token'");
    if(!$query0){
        die('cant query database in query0, sorry.');
    }
    
    if($query0 -> num_rows == 0){
        die('token doesnt exists');
    }
    
    $query0 = mysqli_fetch_array($query0);
    
    if(boolval($query0['is valid']) == false){
        die('this token is no more valid');
    }
    
    if(date('Y-m-d H:i:s', strtotime('+0 days')) > date('Y-m-d H:i:s', strtotime($query0['expires at']))){
        
        $query1 = mysqli_query($connection, "UPDATE `tokens` SET `is valid`='0'");
        if(!$query1){
            die('cannot update the token validation');
        }
        
        
        die('this token has been expired');
    }
    
    
    // IO actions
    //
    // WARNINGS
    // 
    // The sent directory ($directory) ever starts with '/' and ever ends without '/'
    //
    if(file_exists('../../../Data/Clouds/'.$query0['owner'].$directory)){
        die('Directory already exists');
    }
    if(mkdir('../../../Data/Clouds/'.$query0['owner'].$directory, 0755, true) == false){
        die('cant create directory');
    }
    
    die('directory created');
}

?>