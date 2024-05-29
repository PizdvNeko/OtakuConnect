<style>
    .banner {
        padding: 5rem;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-color: #8f00ff;
        text-align: center;
        color: white;
    }
    .anime-image {
        width: 350px;
    }
    .anime-details {
        background-color: #f8f9fa;
        padding: 2rem;
        border-radius: 10px;
    }
    .form-group label {
        font-weight: bold;
    }
    .btn-primary:hover {
        background-color: #7000cc;
    }
</style>

<?php

function voteAnime($idAnime, $vote) {
    // Aggiorna il voto dell'anime nel database
    $pdo = DBHandler::getPDO();
    $stmt = $pdo->prepare('UPDATE animeuser SET Score = :vote WHERE idAnime = :idAnime AND iduser = :idUser;');
    $stmt->bindParam(':vote', $vote, PDO::PARAM_INT);
    $stmt->bindParam(':idAnime', $idAnime, PDO::PARAM_INT);
    $stmt->bindParam(':idUser', $_SESSION["idUser"], PDO::PARAM_INT);
    $stmt->execute();
    $stmt = $pdo->prepare('UPDATE anime SET AVGScore = (SELECT TRUNCATE(avg(Score), 1) FROM animeuser WHERE idAnime = :idAnime1 AND Score != 0) WHERE idAnime = :idAnime2;');
    $stmt->bindParam(':idAnime1', $idAnime);
    $stmt->bindParam(':idAnime2', $idAnime);
    $stmt->execute();
}

if(isset($_POST["vote"]) && isset($_REQUEST["anime"])) {
    $idAnime = $_REQUEST["anime"];
    $vote = $_POST["vote"];

    // Verifica che il voto sia compreso tra 0 e 5
    if($vote >= 0 && $vote <= 5) {
        // Aggiorna il voto dell'anime
        voteAnime($idAnime, $vote);
    }
}

$prep = DBHandler::getPDO()->prepare('SELECT * FROM anime WHERE idAnime = :idAnime');
$prep->bindParam(':idAnime', $_REQUEST["anime"]);
$prep->execute();
$anime = $prep->fetch(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col banner" style="background-image: url('<?php echo isset($anime["banner_path"]) ? $anime["banner_path"] : ""; ?>');">
            <h1><?php echo isset($anime["Name"]) ? $anime["Name"] : ""; ?></h1>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-6">
            <img src="<?php echo isset($anime["image_path"]) ? $anime["image_path"] : ""; ?>" class="anime-image mx-auto d-block mb-3" alt="<?php echo isset($anime["Name"]) ? $anime["Name"] : ""; ?>">
        </div>
        <div class="col-sm-6">
            <p><strong>Name:</strong> <?php echo isset($anime["Name"]) ? $anime["Name"] : ""; ?></p>
            <p><strong>Description:</strong> <?php echo isset($anime["Description"]) ? $anime["Description"] : ""; ?></p>
            <p><strong>Episodes:</strong> <?php echo isset($anime["Episodes"]) ? $anime["Episodes"] : ""; ?></p>
            <p><strong>Average score:</strong> <?php echo isset($anime["AVGScore"]) ? $anime["AVGScore"] : ""; ?></p>
            <?php 
            if(isset($_SESSION["idUser"])){
                $prep = DBHandler::getPDO()->prepare("SELECT * from animeuser WHERE iduser = :idUser AND idAnime = :idAnime;");
                $prep->bindParam('idUser', $_SESSION["idUser"], PDO::PARAM_INT);
                $prep->bindParam('idAnime', $anime["idAnime"], PDO::PARAM_INT);
                $prep->execute();
                if($prep->rowCount() > 0):
                    $useranime = $prep->fetch(PDO::FETCH_ASSOC);
                ?>
                <p><strong>Your score:</strong> <?php echo isset($useranime["Score"]) ? $useranime["Score"] : ""; ?></p>
            <form method="post" class="mt-4">
                <div class="form-group">
                    <label for="vote">Vota questo anime (da 0 a 5):</label>
                    <input type="number" class="form-control" style="max-width: 100px;" id="vote" name="vote" min="1" max="5" step="0.1" required>
                </div>
                <button type="submit" class="btn btn-primary">Vota</button>
            </form>
            <?php
                endif;
            }
            ?>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="container mt-5">
      <h1>User who watched this anime</h1>


      <div class="container-fluid">
        <h2>Watchers</h2>
        <ul class="list-group">
          <?php
          $communityQuery = "
                        SELECT user.*
                        FROM user 
                        INNER JOIN animeuser ON user.idUser = animeuser.iduser
                        WHERE animeuser.idAnime = :idAnime ORDER BY user.Username";
          $stmt = DBHandler::getPDO()->prepare($communityQuery);
          $stmt->execute(['idAnime' => $_REQUEST["anime"]]);
          $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if (count($users) > 0) {
            foreach ($users as $user) {
              echo '<li class="list-group-item">
                            <a data-bs-toggle="modal" data-bs-target="#modal' . $user["idUser"] . '">
                            <img src="' . $user["image_path"] . '" alt="Not Found" onerror="this.src=\'../images/default.jpg\';" style="width:40px;" class="rounded-pill"> '
                . htmlspecialchars($user['Username']) .
                '</a>
                            </li>';

              echo '<div class="modal" id="modal' . $user["idUser"] . '">';
              echo '<div class="modal-dialog">
                                  <div class="modal-content">
                        
                                    <div class="modal-header">
                                      <h4 class="modal-title">User profile:</h4>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                  <div class="modal-body">
                                    <div class="card mb-4">
                                      <div class="card-body text-center">
                                        <img src="' . $user['image_path'] . '" alt="avatar" onerror="this.src=\'../images/default.jpg\';"
                                          class="rounded-circle img-fluid" style="width: 150px">
                                        <h5 class="my-3">' . $user['Username'] . '</h5>
                                        <p class="text-muted mb-1">Bio:</p>
                                        <p class="text-muted mb-4">' . $user['Bio'] . '</p>
                                        <div class="row align-items-start">
                                          <div class="col">
                                            <p class="text-muted mb-4">Follower: ' . $user['Follower'] . '</p>
                                          </div>
                                          <div class="col">
                                            <p class="text-muted mb-4">Followed: ' . $user['Followed'] . '</p>
                                          </div>
                                        </div>
                                        <div class="d-flex justify-content-center mb-2">
                                            <form action="userpage.php" method="POST">
                                              <input type="hidden" name="idUser" value="' . $user['idUser'] . '" />
                                              <input type="hidden" name="name" value="' . $user['Username'] . '" />
                                              <button type="submit" class="btn btn-primary align-self-end m-2">See profile</button>
                                            </form>';
              if (isset($_SESSION["idUser"])) {
                $query = DBHandler::getPDO()->prepare('SELECT * from follow WHERE follower =' . $_SESSION["idUser"] . ' AND followed =' . $user["idUser"] . ';');
                $query->execute();

                if ($_SESSION["idUser"] != $user["idUser"]) {
                  if ($query->rowCount() > 0) {
                    echo '<button id="follow' . $user["idUser"] . '" class="btn btn-secondary align-self-end m-2" onclick="addFollow(\'' . $_SESSION["idUser"] . '\',\'' . $user["idUser"] . '\',\'' . $user["idUser"] . '\')">Followed</button>';
                  } else {
                    echo '<button id="follow' . $user["idUser"] . '" class="btn btn-primary align-self-end m-2" onclick="addFollow(\'' . $_SESSION["idUser"] . '\',\'' . $user["idUser"] . '\',\'' . $user["idUser"] . '\')">Follow</button>';
                  }
                }
              } else {
                echo '<button type="button" class="btn btn-primary align-self-end m-2" disabled>Follow</button>';
              }
              echo '</div>
                                      </div>
                                    </div>
                                  </div>
                                  </div>
                                  </div>
                                  </div>';
            }
          } else {
            echo '<li class="list-group-item">No users found.</li>';
          }
          ?>
        </ul>
      </div>
    </div>
</div>

