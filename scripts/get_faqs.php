<?php
    // ============================================
    //     - PBSCLP | get_faqs
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script queries and returns all the 
    //     FAQ's for the platform
    // ============================================

    // https://makitweb.com/return-json-response-ajax-using-jquery-php
    $pass = file_get_contents('../../pass.txt', true);

    //connect to database
    try {
        $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
        $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        exit('*database_connection_error*');
    }

    //perform query and sort into newest first
    $sql = "SELECT * FROM faq ORDER BY faq_id ASC";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    
    if ($result){
        //initialise array
        $data = array();

        // output data of each row
        foreach($result as $row) {
            //retrieve data from query
            $question = $row['question'];
            $answer = $row['answer'];
                
            //add data into array
            $data[] = array(
                "question" => $question,
                "answer" => $answer
            );
        }
        
        //encode the array into jason
        echo json_encode($data);
    } else {
        echo json_encode("*warning_no_faqs_found*");
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;

?>