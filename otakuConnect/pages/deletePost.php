<?php

session_start();
$idPost = htmlspecialchars($_POST['idPost']);

try {
    $prep = DBHandler::getPDO()->prepare("DELETE FROM Post where idPost = :idPost");
    
    $prep->bindParam('idPost', $idPost, PDO::PARAM_INT);
    if($prep->execute() == true){
        header('Location:firstpage.php');
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}