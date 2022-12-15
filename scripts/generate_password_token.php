<?php
if (isset($_POST['userId'])) {

    $pass = file_get_contents('../../pass.txt', true);
    $e_pass = file_get_contents('../../e-pass.txt', true);

    //connect to database
    try {
        $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
        $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        exit('*database_connection_error*');
    }

    $user_id = $_POST['userId'];

    $sql = "SELECT email FROM users WHERE user_id=?";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute([$user_id]);
    $data = $stmt->fetch();

    if ($data) {
        // $name = $data['firstname'] . $data['lastname'];
        $email = $data['email'];

        $token = md5($email) . rand(10, 9999);
        $expFormat = mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 2, date("Y"));
        $expDate = date("Y-m-d H:i:s", $expFormat);

        $sql = "UPDATE users set reset_link_token=:token, exp_date=:expDate WHERE email=:email";
        $stmt = $connectionPDO->prepare($sql);

        //check to see if the insert was successful
        if ($stmt->execute(['token' => $token, 'expDate' => $expDate, 'email' => $email])) {
            // echo 'success';
        } else {
            exit("*failed_to_create_token*");
        }

        $link = "https://www.pbsclp.info/pages/change_password.php?key=" . $email . "&token=" . $token;


    } else {
        exit("*no_user_found*");
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;

    exit(json_encode($link));
}
?>