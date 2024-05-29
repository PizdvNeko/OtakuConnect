<?php

$idPost = htmlspecialchars($_REQUEST["idpost"]);
$idUser = htmlspecialchars($_REQUEST["iduser"]);

try{
    $prep = DBHandler::getPDO()->prepare("SELECT * FROM likePost WHERE idpost = :idpost AND iduser = :iduser;");
    $prep->bindParam('idpost', $idPost, PDO::PARAM_INT);
    $prep->bindParam('iduser', $idUser, PDO::PARAM_INT);
    $prep->execute();
    if($prep->rowCount() > 0){
        $query = DBHandler::getPDO()->prepare('CALL deleteLike(:idpost, :iduser);');
        $query->bindParam('idpost', $idPost, PDO::PARAM_INT);
        $query->bindParam('iduser', $idUser, PDO::PARAM_INT);
        $query->execute();
        $query = DBHandler::getPDO()->prepare('SELECT likes FROM post WHERE idpost = :idpost;');
        $query->bindParam('idpost', $idPost, PDO::PARAM_INT);
        $query->execute();
        foreach($query->fetchAll() as $res){
            echo " " . $res["likes"];
        }
    }else{
        $query = DBHandler::getPDO()->prepare('CALL insertLike(:iduser, :idpost);');
        $query->bindParam('idpost', $idPost, PDO::PARAM_INT);
        $query->bindParam('iduser', $idUser, PDO::PARAM_INT);
        $query->execute();
        $query = DBHandler::getPDO()->prepare('SELECT likes FROM post WHERE idpost = :idpost;');
        $query->bindParam('idpost', $idPost, PDO::PARAM_INT);
        $query->execute();
        foreach($query->fetchAll() as $res){
            echo " " . $res["likes"];
        }
    }
}catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}