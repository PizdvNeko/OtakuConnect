<?php
$follower = htmlspecialchars($_REQUEST["follower"]);
$followed = htmlspecialchars($_REQUEST["followed"]);

try{
    $prep = DBHandler::getPDO()->prepare("SELECT * FROM follow WHERE follower = :follower AND followed = :followed;");
    $prep->bindParam('follower', $follower, PDO::PARAM_INT);
    $prep->bindParam('followed', $followed, PDO::PARAM_INT);
    $prep->execute();
    if($prep->rowCount() > 0){
        $query = DBHandler::getPDO()->prepare('DELETE FROM follow WHERE follower = :follower AND followed = :followed;');
        $query->bindParam('follower', $follower, PDO::PARAM_INT);
        $query->bindParam('followed', $followed, PDO::PARAM_INT);
        $query->execute();
    }else{
        $query = DBHandler::getPDO()->prepare('INSERT INTO follow (follower, followed)VALUES(:follower, :followed);');
        $query->bindParam('follower', $follower, PDO::PARAM_INT);
        $query->bindParam('followed', $followed, PDO::PARAM_INT);
        $query->execute();
    }
    $query = DBHandler::getPDO()->prepare('UPDATE user SET Followed = (SELECT count(follower) FROM follow WHERE follower = :follower) WHERE idUser = :follower;');
    $query->bindParam('follower', $follower, PDO::PARAM_INT);
    $query->execute();
    $query = DBHandler::getPDO()->prepare('UPDATE user SET Follower = (SELECT count(followed) FROM follow WHERE followed = :followed) WHERE idUser = :followed;');
    $query->bindParam('followed', $followed, PDO::PARAM_INT);
    $query->execute();
}catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
