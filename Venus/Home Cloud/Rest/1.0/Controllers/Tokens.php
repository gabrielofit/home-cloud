<?php 

// Require all necessary classes
require_once '../Classes/Route.php';
require_once '../Classes/Configurations.php';
require_once '../Classes/Response.php';


// Require All necessary definitions
require_once '../Definitions/Input.php';
require_once '../Definitions/Database.php';


// Set some configurations for php.ini
$configurations = new Configurations();
$configurations -> Setup();


// Set some routes using HTTP verbs
$route = new Route();
$route -> Define('POST', 'OnPOST');
$route -> Define('GET', 'OnGET');
$route -> Run();


// Routes setted for HTTP verbs
function OnGET(){
    
    
    // Necessary classes
    $response = new Response();
    
    
    // Necessary data
    $token = null;
    
    
    // Check if necessary data was sent
    if(isset($_GET['token']) == false){
        $response -> Print(400, 'TOKISS0', 'A token was not set');
    }
    
    
    // Check if necessary data is empty
    if(empty($_GET['token'])){
        $response -> Print(400, 'TOKEMP0', 'Sent token is empty');
    }
    
    
    // Check if necessary data format is correct
    if(preg_match(REGEX_FOR_TOKEN, $_GET['token']) == false){
        $response -> Print(400, 'TOKPRE0', 'Sent token has not a correct format');
    }
    
    
    // Set sent data into variables
    $token = $_GET['token'];
    
    
    // Establishes a connection with database
    $connection = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD);
    if(!$connection){
        $response -> Print(500, 'TOKCON0', 'Internal error when try connect to database');
    }
    
    $selection = mysqli_select_db($connection, DATABASE_NAME);
    if(!$selection){
        $response -> Print(500, 'TOKSEL0', 'Internal error when try select database');
    }
    
    
    // Check if token exists in database
    $query0 = mysqli_query($connection, "SELECT `owner`, `token`, `created at`, `expires at`, `is valid` FROM `tokens` WHERE `token` = '$token'");
    if(!$query0){
        $response -> Print(500, 'TOKQUE0', 'Internal error when try execute query in database');
    }
    
    if($query0 -> num_rows == 0){
        $response -> Print(400, 'TOKQUE1', 'This token does not exists in database');
    }
    
    $query0 = mysqli_fetch_array($query0);
    
    $response -> Print(200, 'TOKGET0', 'Done', array('owner' => intval($query0['owner']), 'token' => $query0['token'], 'createdAt' => $query0['created at'], 'expiresAt' => $query0['expires at'], 'isValid' => boolval($query0['is valid'])));
    
}
function OnPOST(){
    
    
    // Necessary classes
    $response = new Response();
    
    
    // Necessary data
    $email = null;
    $password = null;
    
    
    // Check if necessary data was sent
    if(isset($_POST['email']) == false){
        $response -> Print(400, 'TOKQUE1', 'Email was not set');
    }
    if(isset($_POST['password']) == false){
        $response -> Print(400, 'TOKQUE1', 'Password was not set');
    }
    
    
    // Check if necessary data is empty
    if(empty($_POST['email'])){
        $response -> Print(400, 'TOKQUE1', 'Email is empty');
    }
    if(empty($_POST['password'])){
        $response -> Print(400, 'TOKQUE1', 'Password is empty');
    }
    
    
    // Check if necessary data format is correct
    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false){
        $response -> Print(400, 'TOKQUE1', 'Email has not a correct format');
    }
    if(preg_match(REGEX_FOR_PASSWORD, $_POST['password']) == false){
        $response -> Print(400, 'TOKQUE1', 'Password has not a correct format');
    }
    
    
    // Set sent data into variables
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    
    // Encrypt necessary data
    $email = base64_encode(hash('sha512', $email));
    $password = base64_encode(hash('sha512', $password));


    // Establishes a connection with database
    $connection = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD);
    if(!$connection){
        die('cant establishes a connection with database, try again');
    }
    
    $selection = mysqli_select_db($connection, DATABASE_NAME);
    if(!$selection){
        die('cant select database in current connection');
    }
    
    
    // Check if email exists in database
    $query0 = mysqli_query($connection, "SELECT `identifier` FROM `users` WHERE `email` = '$email' AND `password` = '$password'");
    if(!$query0){
        die('cant query database in query0, sorry.');
    }
    
    if($query0 -> num_rows == 0){
        die('incorrect credentials');
    }
    
    $query0 = mysqli_fetch_array($query0);
    
    
    // Data
    $owner = $query0['identifier'];
    $email = $email;
    $password = $password;
    $createdAt = date('Y-m-d H:i:s', strtotime('+0 days'));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));
    
    
    // Generate token
    $token = base64_encode(hash('sha256', $owner.$email.$password.$createdAt.$expiresAt));
    
    
    // Delete user's old token
    $query1 = mysqli_query($connection, "DELETE FROM `tokens` WHERE `owner` = '$owner'");
    if(!$query1){
        die('erro to execute query');
    }
    
    
    // Insert the new token into database
    $query2 = mysqli_query($connection, "INSERT INTO `tokens` (`owner`, `token`, `created at`, `expires at`) VALUES  ('$owner', '$token', '$createdAt', '$expiresAt')");
    if(!$query2){
        die('cant insert the new token into database, try again');
    }
    
    $response -> Print(200, 'TOKPOS0', 'Token generated.', array('owner' => $owner, 'token' => $token, 'createdAt' => $createdAt, 'expiresAt' => $expiresAt));
}

?>