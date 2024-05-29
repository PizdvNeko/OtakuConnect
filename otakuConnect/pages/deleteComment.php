<?php

session_start();
$idComment = htmlspecialchars($_POST['idComment']);

try {
    $prep = DBHandler::getPDO()->prepare("DELETE FROM Comment where idComment = :idComment");
    
    $prep->bindParam('idComment', $idComment, PDO::PARAM_INT);
    if($prep->execute() == true){
        header('Location:firstpage.php');
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}