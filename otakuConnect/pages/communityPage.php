<?php
    $query = "SELECT * FROM community";
    $statement = DBHandler::getPDO()->prepare($query);
    $statement->execute();
    $communities = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    // Funzione per controllare se l'utente è iscritto a un gruppo
    function isUserSubscribed($idCommunity, $idUser) {
        $query = "SELECT COUNT(*) as count FROM usercommunity WHERE idCommunity = :idCommunity AND idUser = :idUser";
        $statement = DBHandler::getPDO()->prepare($query);
        $statement->bindParam(':idCommunity', $idCommunity);
        $statement->bindParam(':idUser', $idUser);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    // Gestione dell'iscrizione
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['subscribe'])) {
            $idCommunity = $_POST['idCommunity'];
            $idUser = $_SESSION['idUser'];
            $query = "INSERT INTO usercommunity (idCommunity, idUser) VALUES (:idCommunity, :idUser)";
        } elseif (isset($_POST['unsubscribe'])) {
            $idCommunity = $_POST['idCommunity'];
            $idUser = $_SESSION['idUser'];
            $query = "DELETE FROM usercommunity WHERE idCommunity = :idCommunity AND idUser = :idUser";
        } elseif (isset($_POST['delete'])) {
            $idCommunity = $_POST['idCommunity'];
            // Cancella la community dal database
            $query = "DELETE FROM community WHERE idCommunity = :idCommunity";
        }
        $statement = DBHandler::getPDO()->prepare($query);
        $statement->bindParam(':idCommunity', $idCommunity);
        if (isset($idUser)) {
            $statement->bindParam(':idUser', $idUser);
        }
        $statement->execute();
        // Redirect per evitare il reinoltro del modulo
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    if(isset($_SESSION["idUser"])):
?>

<div class="container d-flex flex-column mt-5">
    <a href="createCommunity.php" class="btn btn-success">Crea una nuova community</a>
</div>
<?php endif;?>

<div class="container mt-5">
    <h1>Anime Community</h1>
    <div class="row">
        <?php foreach ($communities as $community): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="<?php echo $community['picture_path']; ?>" class="card-img-top" alt="<?php echo $community['Name']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $community['Name']; ?></h5>
                    <p class="card-text"><?php echo $community['Description']; ?></p>
                    <?php if (isset($_SESSION['idUser'])): ?>
                        <?php if (isUserSubscribed($community['idCommunity'], $_SESSION['idUser'])): ?>
                            <form method="post" class="d-flex justify-content-between align-items-center">
                                <input type="hidden" name="idCommunity" value="<?php echo $community['idCommunity']; ?>">
                                <button type="submit" name="unsubscribe" class="btn btn-danger mr-2">Disiscriviti</button>
                        <?php else: ?>
                            <form method="post" class="d-flex justify-content-between align-items-center">
                                <input type="hidden" name="idCommunity" value="<?php echo $community['idCommunity']; ?>">
                                <button type="submit" name="subscribe" class="btn btn-primary">Iscriviti</button>
                        <?php endif; ?>
                        <a href="community_posts.php?idCommunity=<?php echo $community['idCommunity']; ?>&name=<?php echo $community['Name']; ?>" class="btn btn-primary">Visualizza i post</a>
                        <?php 
                        $query = "SELECT * FROM user WHERE idUser = :idUser;";
                        $statement = DBHandler::getPDO()->prepare($query);
                        $statement->bindParam(':idUser', $_SESSION['idUser']);
                        $statement->execute();
                        $user = $statement->fetch(PDO::FETCH_ASSOC);;
                        if($community["idCreator"] == $_SESSION['idUser'] || $user["Admin"] == 1): ?>
                        <button type="submit" name="delete" class="btn btn-danger">Elimina</button>
                        <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
