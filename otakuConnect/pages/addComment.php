<?php

session_start();
$text = htmlspecialchars($_POST['text']);
$idPost = htmlspecialchars($_POST['idPost']);

if($text == ""){
    $_SESSION['Warning-comment'] = true;
    header('Location:firstpage.php');
    exit();
}

try {
    $prep = DBHandler::getPDO()->prepare("INSERT INTO Comment(Text,idUser,date, idPost)VALUES(:text, :idUser, CURRENT_TIMESTAMP, :idPost);");
    $prep->bindParam('text', $text, PDO::PARAM_STR);
    $prep->bindParam('idUser', $_SESSION["idUser"], PDO::PARAM_STR);
    $prep->bindParam('idPost', $idPost, PDO::PARAM_INT);
    if($prep->execute() == true){
        header('Location:firstpage.php');
    } else {
        
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}