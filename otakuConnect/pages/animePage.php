<script>
  function searchHint(str) {
    if (str.length == 0) {
      document.getElementById("searchResults").innerHTML = "";
      return;
    } else {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("searchResults").innerHTML = '<div class="h3 mx-auto pt-5"> Search result: </div>' + this.responseText;
        }
      };
      xmlhttp.open("GET", "search.php?search=" + str, true);
      xmlhttp.send();
    }
  }

  function addFollow(idUser, idAnime) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var post = document.getElementById("anime" + idAnime);
        if (post.innerHTML == "Follow") {
          post.innerHTML = "Followed";
          post.setAttribute("class", "btn btn-secondary align-self-end m-2");
        } else {
          post.innerHTML = "Follow";
          post.setAttribute("class", "btn btn-primary align-self-end m-2");
        }
      }
    };
    xmlhttp.open("GET", "insertFollowAnime.php?idUser=" + idUser + "&idAnime=" + idAnime, true);
    xmlhttp.send();
  }
</script>

<style>
  .card {
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .card-body {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
  }

  .description {
    overflow: hidden;
    max-height: 5em;
    line-height: 1.2em;
    transition: max-height 0.2s ease-out;
  }

  .description.expanded {
    max-height: none;
  }

  .read-more {
    cursor: pointer;
    color: #007bff;
    text-decoration: underline;
  }
</style>

<?php
if(isset($_SESSION["idUser"]) && $rows[0]['Admin'] == 1):
?>

<div class="container d-flex flex-column mt-5">
    <a href="animeForm.php" class="btn btn-success">Inserisic un nuovo anime</a>
</div>
<?php endif;?>

<div class="container mt-5">
  <!-- Barra di ricerca -->
  <form id="searchForm" action="">
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input type="text" class="form-control" id="searchInput" name="search" placeholder="Cerca..." onkeyup="searchHint(this.value)">
    </div>
  </form>

  <div class="row mt-3" id="searchResults">
    <!-- Qui verranno visualizzati i risultati della ricerca -->
  </div>

  <div class="h3 mx-auto pt-5">Anime List:</div>
  <div class="row">

    <?php
    $prep = DBHandler::getPDO()->prepare('SELECT * FROM anime ORDER BY Name');
    $prep->execute();

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
    ?>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  function searchHint(str) {
    if (str.length == 0) {
      document.getElementById("searchResults").innerHTML = "";
      return;
    } else {
      axios.get("search.php?search=" + str)
        .then(function(response) {
          document.getElementById("searchResults").innerHTML = '<div class="h3 mx-auto pt-5"> Search result: </div>' + response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    const readMoreButtons = document.querySelectorAll('.read-more');
    readMoreButtons.forEach(button => {
      button.addEventListener('click', function() {
        const description = this.previousElementSibling;
        if (description.classList.contains('expanded')) {
          description.classList.remove('expanded');
          this.textContent = 'Read more';
        } else {
          description.classList.add('expanded');
          this.textContent = 'Read less';
        }
      });
    });
  });
</script>