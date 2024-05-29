<?php
    session_start();
    if($_POST['community'] == ""){
        $idCommunity = null;
    }else{
        $idCommunity = htmlspecialchars($_POST['community']);
    }
    $text = htmlspecialchars($_POST['postText']);
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
            header('Location:firstpage.php');
            exit();
        }
    }
    if($text == ""){
        $_SESSION['Warning-post'] = true;
        header('Location:firstpage.php');
        exit();
    }

    try {
        $prep = DBHandler::getPDO()->prepare("INSERT INTO post(image_path,description,idUser,date, idCommunity)VALUES(:image, :description, :idUser, CURRENT_TIMESTAMP, :idCommunity);");
        $prep->bindParam('image', $file, PDO::PARAM_STR);
        $prep->bindParam('description', $text, PDO::PARAM_STR);
        $prep->bindParam('idUser', $_SESSION["idUser"], PDO::PARAM_INT);
        $prep->bindParam('idCommunity', $idCommunity, PDO::PARAM_INT);
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
            header('Location:firstpage.php');
        } else {

        }
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>