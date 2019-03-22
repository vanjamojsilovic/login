<?php
require 'dbFunctions.php';
require 'utilFunctions.php';


if ($_SERVER["REQUEST_METHOD"] == "POST"){ 
    if(!empty($_POST['first_name']) && isset($_POST['first_name'])){
        $first_name = sanatize_input($_POST['first_name']);    
    }
    $last_name = sanatize_input($_POST['last_name']);
    $email = sanatize_input($_POST['email']);
    $password = sanatize_input($_POST['password']);
    $confirm = sanatize_input($_POST['confirm']);

    $db_data = new dbFunctions();

    $sql_response = $db_data->insert_employee_login($first_name, $last_name, $email, $password, $confirm);  
    
    switch ($sql_response) {
    case 1000:
        header("HTTP/1.1 200 OK");
        $response=array('successful'=>TRUE,'message_code'=>1000, 'message'=>'Correct insertion!');
        break;
    case 1001:
        header("HTTP/1.1 401");
        $response=array('successful'=>FALSE,'message_code'=>1001, 'message'=>'Please, re-enter your password!');
        break;    
    }
    echo json_encode($response);
        
}

