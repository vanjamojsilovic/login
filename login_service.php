<?php
require 'dbFunctions.php';
require 'utilFunctions.php';
$response="";

if ($_SERVER["REQUEST_METHOD"] == "POST"){     
    
    $email = sanatize_input($_POST['email']);
    $password = sanatize_input($_POST['password']);
    
    
    $db_data = new dbFunctions();

    $sql_response = $db_data->login($email, $password);  

    switch ($sql_response) {
    case 1000:
        header("HTTP/1.1 200 OK");
        $response=array('successful'=>TRUE,'message_code'=>1000, 'message'=>'Correct login!');
        break;
    case 1001:
        header("HTTP/1.1 401");
        $response=array('successful'=>FALSE,'message_code'=>1001,'message'=>'Incorrect password! Try again!');
        break;
    case 1002:
        header("HTTP/1.1 401");
        $response=array('successful'=>FALSE,'message_code'=>1002, 'message'=>'User doesn\'t exsist! Please check the email!');
        break;
    }

    echo json_encode($response);
   
    
               
}

