<?php
$idUser = htmlspecialchars($_REQUEST["idUser"]);
$idAnime = htmlspecialchars($_REQUEST["idAnime"]);

try{
    $prep = DBHandler::getPDO()->prepare("SELECT * from animeuser WHERE iduser = :idUser AND idAnime = :idAnime;");
    $prep->bindParam('idUser', $idUser, PDO::PARAM_INT);
    $prep->bindParam('idAnime', $idAnime, PDO::PARAM_INT);
    $prep->execute();
    if($prep->rowCount() > 0){
        $query = DBHandler::getPDO()->prepare('DELETE FROM animeuser WHERE iduser = :idUser AND idAnime = :idAnime;');
        $query->bindParam('idUser', $idUser, PDO::PARAM_INT);
        $query->bindParam('idAnime', $idAnime, PDO::PARAM_INT);
        $query->execute();
    }else{
        $query = DBHandler::getPDO()->prepare('INSERT INTO animeuser (iduser, idAnime)VALUES(:idUser, :idAnime);');
        $query->bindParam('idUser', $idUser, PDO::PARAM_INT);
        $query->bindParam('idAnime', $idAnime, PDO::PARAM_INT);
        $query->execute();
    }
}catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
