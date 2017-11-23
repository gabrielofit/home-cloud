<?php 

// Require all necessary classes
require_once '../Classes/Route.php';
require_once '../Classes/Configurations.php';


// Require All necessary definitions
require_once '../Definitions/Input.php';
require_once '../Definitions/Database.php';


// Set some configurations for php.ini
$configurations = new Configurations();
$configurations -> Setup();


// Set some routes using HTTP verbs
$route = new Route();
$route -> Define('POST', 'OnPOST');
$route -> Run();


// Routes setted for HTTP verbs
function OnPOST(){
    
    
    // Necessary data
    $token = null;
    $directory = null;
    $file = null;
    
    
    // Check if necessary data was sent
    if(isset($_POST['token']) == false){
        die('token not set');
    }
    if(isset($_POST['directory']) == false){
        die('directory not set');
    }
    if(isset($_FILES['file']) == false){
        die('file not set');
    }
    
    
    // Check if necessary data is empty
    if(empty($_POST['token'])){
        die('token is empty');
    }
    if(empty($_POST['directory'])){
        die('directory is empty');
    }
    
    
    // Check if necessary data format is correct
    if(preg_match(REGEX_FOR_TOKEN, $_POST['token']) == false){
        die('token is invalid');
    }
    
    if(preg_match(REGEX_FOR_DIRECTORY, $_POST['directory']) == false){
        die('directory is invalid');
    }
    
    
    // Set sent data into variables
    $token = $_POST['token'];
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
    if(file_exists('../../../Data/Clouds/'.$query0['owner'].$directory) == false){
        if(mkdir('../../../Data/Clouds/'.$query0['owner'].$directory) == false){
            die('directory didnt exists, we try create, but no success');
        }
    }
    
    $temporaryFileDirectory = $_FILES['file']['tmp_name'];
    $newFileDirectory = '../../../Data/Clouds/'.$query0['owner'].$directory.'/'.$_FILES['file']['name'];

    if(move_uploaded_file($temporaryFileDirectory, $newFileDirectory) == false){
        die('cant move uploaded file');
    }
    
    die('file uploaded');
}

?>