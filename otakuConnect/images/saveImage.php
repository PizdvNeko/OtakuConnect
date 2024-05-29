<?php
// Check if file was uploaded without errors
if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0){
    $targetDir = "../images/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);

    // Move the uploaded file to the target directory
    if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)){
        echo "The file ". basename($_FILES["file"]["name"]). " has been uploaded.";
    } else{
        echo "Sorry, there was an error uploading your file.";
    }
} else{
    echo "No file was uploaded.";
}
?>
