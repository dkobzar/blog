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

function addPost($userId, $title, $body, $filePath = false) {
    $userDb = fopen("db/$userId.db", "a+"); //пост юзера помещается в отдельный файл для каждого юзера
    if (!$userDb){
        return false;
    }

    /*добавление изображения в пост, генерация имени файла, перемещение его в папку img*/
    $name = false;
    if (
        $filePath &&
        is_uploaded_file($filePath)
    ) {
        //TODO: check image (getimagesize)
        $pathInfo = pathinfo($filePath); // функция генерирует путь к файлу
        $name = "img_" . time() . "." . $pathInfo['extension'];

        move_uploaded_file($filePath, "img/" . $name); // перемещает файл в папку img
    }

    fwrite($userDb, json_encode([
        'title' => $title,
        'body' => $body,
        'image' => $name,
        'createdAt' => date("d.m.Y H:i:s"),
    ]));
    fclose($userDb);
    return true;
}
?>