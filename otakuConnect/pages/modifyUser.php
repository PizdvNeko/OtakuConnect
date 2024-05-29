<?php
session_start();

if(isset($_POST["username"])){
    $newUsername = htmlspecialchars($_POST["username"]);
    try{
        $prep = DBHandler::getPDO()->prepare('UPDATE user SET Username = :newUsername WHERE iduser=:idUser;');
        $prep->bindParam('idUser', $_SESSION["idUser"], PDO::PARAM_INT);
        $prep->bindParam('newUsername', $newUsername, PDO::PARAM_STR);
        if($prep->execute()){
            header("Location:userOption.php");
        }else{
            header("Location:userOptionModify.php");
        }
    }catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

if(isset($_POST["bio"])){
    $newBio = htmlspecialchars($_POST["bio"]);
    try{
        $prep = DBHandler::getPDO()->prepare('UPDATE user SET Bio = :newBio WHERE iduser=:idUser;');
        $prep->bindParam('idUser', $_SESSION["idUser"], PDO::PARAM_INT);
        $prep->bindParam('newBio', $newBio, PDO::PARAM_STR);
        if($prep->execute()){
            header("Location:userOption.php");
        }else{
            header("Location:userOptionModify.php");
        }
    }catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

if(isset($_POST["email"])){
    $newEmail = htmlspecialchars($_POST["email"]);
    try{
        $prep = DBHandler::getPDO()->prepare('UPDATE user SET Email = :newEmail WHERE iduser=:idUser;');
        $prep->bindParam('idUser', $_SESSION["idUser"], PDO::PARAM_INT);
        $prep->bindParam('newEmail', $newEmail, PDO::PARAM_STR);
        if($prep->execute()){
            header("Location:userOption.php");
        }else{
            header("Location:userOptionModify.php");
        }
    }catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
 
if(isset($_FILES["file"])){
    if(basename($_FILES["file"]["name"] == "")){
        $file = "";
    }else{
        $folder = "../images/";
        $file = $folder . basename($_FILES["file"]["name"]);
        $imageFileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
            $_SESSION['Warning-image'] = true;
            header('Location:userOptionModify.php');
            exit();
        }
    }
    try{
        $prep = DBHandler::getPDO()->prepare('UPDATE user SET image_path = :image WHERE iduser=:idUser;');
        $prep->bindParam('idUser', $_SESSION["idUser"], PDO::PARAM_INT);
        $prep->bindParam('image', $file, PDO::PARAM_STR);
        if($prep->execute() == true){
            if($file != ""){
                $uploadOk = 1;
                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["file"]["tmp_name"]);
                if($check !== false) {
                        echo "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        echo "File is not an image.";
                        $uploadOk = 0;
                    }
                }
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
                            echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                    }
                }
            }
            header("Location:userOption.php");
        }else{
            header("Location:userOptionModify.php");
        }
    }catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

if(isset($_POST["newpw"])){
    $password = htmlspecialchars($_POST["newpw"]);
    try{
        $prep = DBHandler::getPDO()->prepare('SELECT * FROM user WHERE iduser=:idUser;');
        $prep->bindParam('idUser', $_SESSION["idUser"], PDO::PARAM_INT);
        $prep->execute();
        $row = $prep->fetch(PDO::FETCH_ASSOC);
        if(isset($_POST["oldpw"]) && password_verify($_POST["oldpw"], $row["Password"])){
            if(isset($_POST["newpw2"]) && $password === $_POST["newpw2"]){
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $prep = DBHandler::getPDO()->prepare('UPDATE user SET Password = :newPassword WHERE iduser=:idUser;');
                $prep->bindParam('idUser', $_SESSION["idUser"], PDO::PARAM_INT);
                $prep->bindParam('newPassword', $hash, PDO::PARAM_STR);
                if($prep->execute()){
                    header("Location:userOption.php");
                }else{
                    header("Location:userOptionModify.php");
                }
            }else{
                $_SESSION['Warning-newpw'] = true;
                header("Location:userOptionModify.php");
            }
        }else{
            $_SESSION['Warning-oldpw'] = true;
            header("Location:userOptionModify.php");
        }
    }catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    
}