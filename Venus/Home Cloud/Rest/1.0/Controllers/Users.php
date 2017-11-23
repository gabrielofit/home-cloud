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
$route -> Run();


// Routes setted for HTTP verbs
function OnPOST(){
    
    
    // Necessary classes
    $response = new Response();
    
    
    //
    // **** EXPLANATION ****
    //
    // At this point, we need check if the user has been
    // set all necessary data we need, for exemple: name,
    // lastname, email ...
    //
    if(isset($_POST['name']) == false){
        $response -> Print(400, 'USEPOSISS0', 'A name was not set');
    }
    if(isset($_POST['lastname']) == false){
        $response -> Print(400, 'USEPOSISS1', 'A lastname was not set ');
    }
    if(isset($_POST['username']) == false){
        $response -> Print(400, 'USEPOSISS2', 'An username was not set');
    }
    if(isset($_POST['email']) == false){
        $response -> Print(400, 'USEPOSISS3', 'An email was not set');
    }
    if(isset($_POST['password']) == false){
        $response -> Print(400, 'USEPOSISS4', 'A password was not set');
    }
    
    
    //
    // **** EXPLANATION ****
    //
    // At this point, we need check if the sent data
    // has a correct format
    //
    if(preg_match(REGEX_FOR_NAME, $_POST['name']) == false){
        $response -> Print(400, 'USEPOSPRE0', 'Name has a wrong format');
    }
    if(preg_match(REGEX_FOR_LASTNAME, $_POST['lastname']) == false){
        $response -> Print(400, 'USEPOSPRE1', 'Lastname has a wrong format');
    }
    if(preg_match(REGEX_FOR_USERNAME, $_POST['username']) == false){
        $response -> Print(400, 'USEPOSPRE2', 'Username has a wrong format');
    }
    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false){
        $response -> Print(400, 'USEPOSPRE3', 'Email has a wrong format');
    }
    if(preg_match(REGEX_FOR_PASSWORD, $_POST['password']) == false){
        $response -> Print(400, 'USEPOSPRE4', 'Password has a wrong format');
    }
    
    
    // Encrypt necessary data
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = base64_encode(hash('sha512', $_POST['email']));
    $password = base64_encode(hash('sha512', $_POST['password']));
    
    
    // Establishes a connection with database
    $connection = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD);
    if(!$connection){
        $response -> Print(500, 'USECON0', 'Internal error when try connect to database');
    }
    
    $selection = mysqli_select_db($connection, DATABASE_NAME);
    if(!$selection){
        $response -> Print(500, 'USESEL0', 'Internal error when try select database');
    }
    
    
    // Check if the email sent by user is available
    $query0 = mysqli_query($connection, "SELECT `email` FROM `users` WHERE `email` = '$email'");
    if(!$query0){
        $response -> Print(500, 'USEQUE0', 'Internal error when try execute query in database');
    }
    
    if($query0 -> num_rows == 1){
        $response -> Print(200, 'USEQUE1', 'This email is not available');
    }
    
    
    // Check if the username sent by user is available
    $query0 = mysqli_query($connection, "SELECT `username` FROM `users` WHERE `username` = '$username'");
    if(!$query0){
        $response -> Print(500, 'USEQUE2', 'Internal error when try execute query in database');
    }
    
    if($query0 -> num_rows == 1){
        $response -> Print(200, 'USEQUE3', 'This username is not available');
    }
    
    
    // Insert user into database
    $query1 = mysqli_query($connection, "INSERT INTO `users` (`name`, `lastname`, `email`, `password`, `username`) VALUES ('$name', '$lastname', '$email', '$password', '$username')");
    if(!$query1){
        $response -> Print(500, 'USEQUE4', 'Internal error when try execute query in database');
    }
    
    
    $cloudOwner = mysqli_insert_id($connection);
    
    
    // Insert a new cloud into database
    $query2 = mysqli_query($connection, "INSERT INTO `clouds` (`owner`) VALUES ('$cloudOwner')");
    if(!$query2){
        $response -> Print(500, 'USEQUE4', 'Internal error when try execute query in database');
    }
    
    
    // IO actions
    if(mkdir('../../../Data/Clouds/' . $cloudOwner, 0755, true) == false){
        $response -> Print(500, 'USEMKD0', 'Internal error when try create user cloud');
    }
    
    // Return a response
    $response -> Print(200, 'USEPOS0', 'User registred');
}

?>