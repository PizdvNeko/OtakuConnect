<?php
    session_start();
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['pswd']);

    try {
        $prep = DBHandler::getPDO()->prepare("SELECT * FROM user WHERE username = :username;");
        $prep->bindParam('username', $username, PDO::PARAM_STR);
        $prep->execute();
        if($prep->rowCount() != 0){
            $rows = $prep->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $rows["Password"])){
                $_SESSION['idUser'] = $rows['idUser'];
                header('Location:firstpage.php');
            } else {
                $_SESSION['Warning-notfound'] = "Incorrect password. Please, try again.";
                header('Location:loginForm.php');
            }
        }else{
            $_SESSION['Warning-notfound'] = "User not found";
            header('Location:loginForm.php');
            exit();
        }
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>