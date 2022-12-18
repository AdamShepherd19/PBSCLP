<?php
// ============================================
//     - PBSCLP | get_user_accounts
//     - Adam Shepherd
//     - PBSCLP
//     - April 2022

//     This script queries and returns a list
//     of all user accounts bar the signed
//     in user
// ============================================

session_start();

// https://makitweb.com/return-json-response-ajax-using-jquery-php
$pass = file_get_contents('../../pass.txt', true);

//connect to database
try {
    $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
    $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('*database_connection_error*');
}

try {
    $usersMemberOrganisations = getUsersWithMemberOrganisation($connectionPDO);

    foreach ($usersMemberOrganisations as $user) {
        $userIdsNoMemberOrg .= $user['user_id'] .= ",";
    }
    $userIdsNoMemberOrg = substr($userIdsNoMemberOrg, 0, -1);

    $userWithNoMemberOrganisation = getUsersWithNoMemberOrganisation($connectionPDO, $userIdsNoMemberOrg);

    $allUsers = array_merge($usersMemberOrganisations, $userWithNoMemberOrganisation);

    try {
        usort($allUsers, fn($a, $b) => $a['firstname'] <=> $b['firstname']);
    } catch (PDOException $e) {
        exit($e);
    }

} catch (PDOException $e) {
    exit($e);
}

echo json_encode($allUsers);

// close connection to db
$stmt = null;
$connectionPDO = null;

function getUsersWithMemberOrganisation($connectionPDO)
{
    $sql = "SELECT users.user_id, users.firstname, users.lastname, users.email, users.contact_number, organisations.organisation_name, users.organisation, users.admin_locked, users.last_login FROM users, organisations, users_in_organisation WHERE users.user_id<>? AND users.user_id = users_in_organisation.user_id AND users_in_organisation.organisation_id = organisations.organisation_id ORDER BY firstname ASC";


    $stmt = $connectionPDO->prepare($sql); //prepare query
    $stmt->execute([$_SESSION['user_id']]); //execute query using data provided
    $result = $stmt->fetchAll(); //fetch results from query

    $listOfUsers = array();
    //check that there were announcements to show
    if ($result) {
        foreach ($result as $row) {
            //add data into array
            $listOfUsers[] = array(
                "user_id" => $row['user_id'],
                "firstname" => $row['firstname'],
                "lastname" => $row['lastname'],
                "email" => $row['email'],
                "contact_number" => $row['contact_number'],
                "member_organisation" => $row['organisation_name'],
                "organisation" => $row['organisation'],
                "admin_locked" => $row['admin_locked'],
                "last_login" => $row['last_login'],
            );
        }
    }

    return $listOfUsers;
}

function getUsersWithNoMemberOrganisation($connectionPDO, $listOfUsersWithMemberOrganisation)
{
    if ($listOfUsersWithMemberOrganisation) {
        $sql = "SELECT users.user_id, users.firstname, users.lastname, users.email, users.contact_number, users.organisation, users.admin_locked, users.last_login FROM users WHERE users.user_id<>? AND users.user_id NOT IN (" . $listOfUsersWithMemberOrganisation . ") ORDER BY `users`.`user_id` ASC";
    } else {
        $sql = "SELECT users.user_id, users.firstname, users.lastname, users.email, users.contact_number, users.organisation, users.admin_locked, users.last_login FROM users WHERE users.user_id<>? ORDER BY `users`.`user_id` ASC";
    }



    $stmt = $connectionPDO->prepare($sql); //prepare query
    $stmt->execute([$_SESSION['user_id']]); //execute query using data provided
    $result = $stmt->fetchAll(); //fetch results from query

    $listOfUsers = array();
    //check that there were announcements to show
    if ($result) {
        foreach ($result as $row) {
            //add data into array
            $listOfUsers[] = array(
                "user_id" => $row['user_id'],
                "firstname" => $row['firstname'],
                "lastname" => $row['lastname'],
                "email" => $row['email'],
                "contact_number" => $row['contact_number'],
                "member_organisation" => '',
                "organisation" => $row['organisation'],
                "admin_locked" => $row['admin_locked'],
                "last_login" => $row['last_login'],
            );
        }
    }

    return $listOfUsers;
}
?>