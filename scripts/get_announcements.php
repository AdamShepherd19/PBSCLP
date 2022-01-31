<?php
    // https://makitweb.com/return-json-response-ajax-using-jquery-php
    $pass = file_get_contents('../../pass.txt', true);

    //connect to database
    $connection = new mysqli('localhost', 'pbsclp', $pass, 'pbsclp_pbsclp');

    //check db connection
    if ($connection->connect_error) {
        exit("Connection failed: " . $connection->connect_error);
    }

    //perform query and sort into newest first
    $query = "SELECT * FROM `announcements` ORDER BY announcement_id DESC";
    $result = $connection->query($query);

    //check that there were announcements to show
    if ($result->num_rows > 0) {

        //initialise array
        $data = array();

        // output data of each row
        while($row = $result->fetch_assoc()) {
            //retrieve data from query
            $id = $row['announcement_id'];
            $title = $row['title'];
            $content = $row['content'];
            $author = $row['author'];
            
            //add data into array
            $data[] = array(
                "id" => $id,
                "title" => $title,
                "content" => $content,
                "author" => $author
            );
        }

        //encode the array into jason
        echo json_encode($data);

    } else {
        echo json_encode("*warning_no_announcements_found*");
    }

    $connection->close();

?>