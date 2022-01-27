<?php
    $pass = file_get_contents('../pass.txt', true);

    //connect to database
    $connection = new mysqli('localhost', 'pbsclp', $pass, 'pbsclp_pbsclp');

    //check db connection
    if ($connection->connect_error) {
        exit("Connection failed: " . $connection->connect_error);
    }

    //perform query
    $query = "SELECT * FROM `announcements`";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {

        $data = array();

        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "- Title: " . $row["title"]. "<br>- Content: " . $row["content"]. "<br>- Author" . $row["author"]. "<br><br>";
            $id = $row['announcement_id'];
            $title = $row['title'];
            $content = $row['content'];
            $author = $row['author'];
            
            $data[] = array(
                "id" => $id,
                "title" => $title,
                "content" => $content,
                "author" => $author
            );
        }

        echo json_encode($data);

    } else {
        echo "0 results";
    }

    $connection->close();

?>