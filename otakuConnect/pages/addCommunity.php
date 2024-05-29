<?php
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $idCreator = htmlspecialchars($_POST["idCreator"]);
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
            header('Location:communityPage.php');
            exit();
        }
    }
    // Inserisci i dati nel database
    $query = "INSERT INTO community (Name, Description, picture_path, idCreator) VALUES (:name, :description, :picture_path, :idCreator)";
    $statement = DBHandler::getPDO()->prepare($query);
    $statement->bindParam(':name', $name);
    $statement->bindParam(':description', $description);
    $statement->bindParam(':picture_path', $file);
    $statement->bindParam(':idCreator', $idCreator);
    if($statement->execute() == true){
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
    }
    // Redirect dopo la creazione della community
    header('Location: communityPage.php');
    exit;
?>