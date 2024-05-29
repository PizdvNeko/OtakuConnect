<?php
session_start();
if (isset($_REQUEST['search'])) {
    $search = '%' . $_REQUEST['search'] . '%';
    
    $prep = DBHandler::getPDO()->prepare('SELECT * FROM anime WHERE Name LIKE :search ORDER BY Name');
    $prep->bindParam(':search', $search, PDO::PARAM_STR);
    $prep->execute();
    
    $resultsHtml = '';
    foreach ($prep->fetchAll() as $anime) {
        echo '
          <div class="col-md-6 mb-4">
              <div class="card">
                  <img src="' . $anime["banner_path"] . '" class="card-img-top" alt="' . $anime["Name"] . '">
                  <div class="card-body">
                      <div class="row">
                          <div class="col-md-4">
                              <img src="' . $anime["image_path"] . '" class="card-img-top" alt="' . $anime["Name"] . '">
                          </div>
                          <div class="col-md-8">
                              <h5 class="card-title">' . $anime["Name"] . '</h5>
                              <p class="card-text description">' . $anime["Description"] . '</p>
                              <span class="read-more">Read more</span>
                              <p class="card-text">Average Score: ' . $anime["AVGScore"] . '</p>
                              <p class="card-text">Episodes: ' . $anime["Episodes"] . '</p>
                              <a href="animeInspect.php?anime=' . $anime["idAnime"] . '" class="btn btn-primary">See more</a>';
                              
                              if (isset($_SESSION["idUser"])) {
                                $query = DBHandler::getPDO()->prepare('SELECT * from animeuser WHERE idUser =' . $_SESSION["idUser"] . ' AND idAnime =' . $anime["idAnime"] . ';');
                                $query->execute();
  
                                if ($query->rowCount() > 0) {
                                  echo '<button id="anime' . $anime["idAnime"] . '" class="btn btn-secondary align-self-end m-2" onclick="addFollow(\'' . $_SESSION["idUser"] . '\',\'' . $anime["idAnime"] . '\')">Followed</button>';
                                } else {
                                  echo '<button id="anime' . $anime["idAnime"] . '" class="btn btn-primary align-self-end m-2" onclick="addFollow(\'' . $_SESSION["idUser"] . '\',\'' . $anime["idAnime"] . '\')">Follow</button>';
                                }
                              } else {
                                echo '<button type="button" class="btn btn-primary align-self-end m-2" disabled>Follow</button>';
                              }
                    echo '</div>
                      </div>
                  </div>
              </div>
          </div>';
      }
    
    echo $resultsHtml;
} else {
    echo 'Nessun risultato trovato.';
}
?>
