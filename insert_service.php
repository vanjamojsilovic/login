<?php
require 'dbFunctions.php';
require 'utilFunctions.php';
$response="Not a post method!";

if ($_SERVER["REQUEST_METHOD"] == "POST"){     
    $first_name = sanatize_input($_POST['first_name']);
    $last_name = sanatize_input($_POST['last_name']);
    $parents_name = sanatize_input($_POST['parents_name']);
    $jmbg = sanatize_input($_POST['jmbg']);
    $date_of_birth = sanatize_input($_POST['date_of_birth']);
    $gender = sanatize_input($_POST['gender']);
    
    $db_data = new dbFunctions();

    $sql_response = $db_data->insert_employee($first_name, $last_name, $parents_name, $jmbg, $date_of_birth, $gender);  
    
    if ($sql_response === TRUE){
                header("HTTP/1.1 200 OK");
                $response="Succesfully!";
    } 
    else {
        $response="Error!";
    }   
}

echo $response;