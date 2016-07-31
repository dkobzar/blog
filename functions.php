<?php

function addUser ($email, $firstName, $lastName, $password) {
    // TODO: implement user id
    $line = json_encode([
        'email' => $email,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'password' => sha1($password)
    ]);

    $userDb = fopen("db/user.db", "a+");
    if ($userDb) {
        fwrite($userDb, $line . PHP_EOL);
        fclose($userDb);
        return true;
    }

    return false;
}

function userExist ($email) {
    $userDb = fopen("db/user.db", "r");
    if (!$userDb){
        return false;
    } else {
        while (!feof($userDb)) {
            $line = fgets($userDb);
            if ($line) {
                $line = json_decode($line, true);
                if ($email == $line['email']) {
                    fclose($userDb);
                    return true;
                }
            }
        }

        fclose($userDb);
        return false;

    }
}

function checkUser($email, $password) {
    $password = sha1($password);
    $userDb = fopen("db/user.db", "r");
    if (!$userDb) {
        return false;
    } else {
        while (!feof($userDb)){
            $line = fgets($userDb);
            if ($line) {
                $line = json_decode($line, true);
                if (
                    $line['email'] == $email &&
                    $line['password'] == $password
                ) {
                    fclose($userDb);
                    return $line;
                }
            }
        }

        fclose($userDb);
        return false;

    }
}
?>