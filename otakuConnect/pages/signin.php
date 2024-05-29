<?php
    session_start();
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $email = htmlspecialchars($_POST['email']);

    try {
        $prep = DBHandler::getPDO()->prepare("SELECT * FROM user WHERE username = :username;");
        $prep->bindParam('username', $username, PDO::PARAM_STR);
        $prep->execute();
        if($prep->rowCount() == 0){
            $prep = DBHandler::getPDO()->prepare("INSERT INTO user(username,email,password)VALUES(:username, :email, :password);");
            $prep->bindParam('username', $username, PDO::PARAM_STR);
            $prep->bindParam('email', $email, PDO::PARAM_STR);
            $prep->bindParam('password', $hash, PDO::PARAM_STR);
            if($prep->execute() == true){
                $prep = DBHandler::getPDO()->prepare("CALL getUserByUsernamePassword(:username, :password);");
                $prep->bindParam('username', $username, PDO::PARAM_STR);
                $prep->bindParam('password', $hash, PDO::PARAM_STR);
                $prep->execute();
                if($prep->rowCount() > 0){
                    $rows = $prep->fetchAll();
                    $_SESSION['idUser'] = $rows[0]['idUser'];
                    header('Location:firstpage.php');
                }
            } else {
                header('Location:signinForm.php');
            }
        }else{
            $_SESSION['Warning-username'] = true;
            header('Location:signinForm.php');
        }
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>