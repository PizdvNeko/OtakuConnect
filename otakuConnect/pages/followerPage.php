<script>
  addExpandText();

  function addFollow(follower, followed, idPost) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        post = document.getElementById("follow" + idPost);
        if(post.innerHTML == "Follow"){
            post.innerHTML = "Followed";
            post.setAttribute("class", "btn btn-secondary align-self-end m-2");
        } else {
            post.innerHTML = "Follow";
            post.setAttribute("class", "btn btn-primary align-self-end m-2");
        }
        }
    };
    xmlhttp.open("GET", "insertFollow.php?follower=" + follower + "&followed=" + followed, true);
    xmlhttp.send();
  }
</script>

<?php
// Define number of posts per page
$postsPerPage = 10;
// Determine current page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $postsPerPage;

?>


  <!-- LATEST POST -->
  <?php
    $prep = DBHandler::getPDO()->prepare('SELECT * FROM post WHERE idUser IN (SELECT followed FROM follow WHERE follower = :idUser) ORDER BY date DESC LIMIT :start, :limit;');
    $prep->bindParam(':idUser', $_SESSION["idUser"], PDO::PARAM_INT);
    $prep->bindParam(':start', $start, PDO::PARAM_INT);
    $prep->bindParam(':limit', $postsPerPage, PDO::PARAM_INT);
    $prep->execute();

    if($prep->rowCount() > 0){

    echo '<div class="container-fluid d-flex flex-column">';
    
    echo '<div class="h3 mx-auto pt-5"> Followers posts: </div>';

    foreach($prep->fetchAll() as $row) {
      $query = DBHandler::getPDO()->prepare('SELECT * from user where iduser =' . $row["idUser"] . ';');
      $query->execute();
      $user = $query->fetchAll();

      echo '<div class="container-fluid d-flex align-items-center justify-content-center m-3">';
      echo '<div class="card mx-auto" style="width:650px">';
      echo '<div class="card-header">
            <a data-bs-toggle="modal" data-bs-target="#modal'.$user[0]["idUser"].'">
            <h3 class="card-title float-start">
            <img src="'. $user[0]["image_path"] .'" alt="." onerror="this.src=\'../images/default.jpg\';" style="width:40px;" class="rounded-pill">
            ' . $user[0]["Username"] . ' posted: </h3>
            </a>';
            if(isset($_SESSION["idUser"]) && $row["idCommunity"] != ""){  
              $query = DBHandler::getPDO()->prepare('SELECT * from community where idCommunity =' . $row["idCommunity"] . ';');
              $query->execute();
              $community = $query->fetch(PDO::FETCH_ASSOC);
              echo  '<a href="community_posts.php?idCommunity='. $community['idCommunity'].'&name='.$community['Name'].'" class="card-link float-end">Community: '. $community["Name"] .'</a>
                    <br><br><br>';
            }
            if(isset($_SESSION["idUser"])):
      if($rows[0]['Admin'] == 1 || $row["idUser"] == $_SESSION["idUser"]):      
      ?>
        <div class="dropdown dropend float-end">
          <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
            Options
          </button>
          <ul class="dropdown-menu">
            <li>
              <form action="deletePost.php" method="POST">
                <input type="hidden" name="idPost" value="<?php echo $row["idPost"]; ?>" />
                <button class="btn dropdown-item" onclick="this.parentNode.submit()">Delete</button>
              </form>
            </li>
          </ul>
        </div>
      <?php
      endif;
      endif;
      echo "</div>";
      if(isset($row["image_path"]) && $row["image_path"] != "") {
        echo "<img class='card-img-top' src='" . $row["image_path"] . "' alt='Image not found' onerror='this.src=\"../images/default.jpg\";' style='width:100%'>";
      }
      echo '<div class="card-body">';
      echo "<p class='card-text text'>" . $row["Description"] . "</p>";

      if(isset($_SESSION["idUser"])) {
        $query = DBHandler::getPDO()->prepare('SELECT * from likepost WHERE idPost =' . $row["idPost"] . ' AND idUser =' . $_SESSION["idUser"] . ';');
        $query->execute();
        if($query->rowCount() > 0) {
          echo '<button class="btn btn-danger" onclick="addLike(\'' . $row["idPost"] . '\', \'' . $_SESSION["idUser"] . '\')">
                <i class="bi bi-heart-fill" id="' . $row["idPost"] . '"> ' . $row["Likes"] . '</i></button>';
        } else {
          echo '<button class="btn btn-danger" onclick="addLike(\'' . $row["idPost"] . '\', \'' . $_SESSION["idUser"] . '\')">
                <i class="bi bi-heart" id="' . $row["idPost"] . '"> ' . $row["Likes"] . '</i></button>';
        }
      } else {
        echo '<button class="btn btn-danger" disabled>
            <i class="bi bi-heart-fill" id="' . $row["idPost"] . '"> ' . $row["Likes"] . '</i></button>';
      }
      echo "<p class='card-text'>" . $row["date"] . "</p>";


      // COMMENTS
      $query = DBHandler::getPDO()->prepare('SELECT * from comment WHERE idPost =' . $row["idPost"] . ' ORDER BY date DESC;');
      $query->execute();

      echo '<h4>Comments: </h4>';
      if(!($query->rowCount() > 0)) {
        echo "There are no comments here.";
      } else {
        foreach($query->fetchAll() as $comment) {
          $query = DBHandler::getPDO()->prepare('SELECT username from user where iduser =' . $comment["idUser"] . ';');
          $query->execute();
          $usernameComment = $query->fetchAll();
          echo '<div class="card">';
          echo '<div class="card-header">
                 <h5 class="card-title float-start">' . $usernameComment[0]["username"] . ' commented: </h5>';
          if(isset($_SESSION["idUser"])):
          if($rows[0]['Admin'] == 1 || $comment["idUser"] == $_SESSION["idUser"]):
          ?>
            <div class="dropdown dropend float-end">
              <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                Options
              </button>
              <ul class="dropdown-menu">
                <li>
                  <form action="deleteComment.php" method="POST">
                    <input type="hidden" name="idComment" value="<?php echo $comment["idComment"]; ?>" />
                    <button class="btn dropdown-item" onclick="this.parentNode.submit()">Delete</button>
                  </form>
                </li>
              </ul>
            </div>
          <?php
          endif;
          endif;
          echo '</div>
                <div class="card-body">';
          echo "<p class='card-text text'>" . $comment["Text"] . "</p>";
          echo "<p class='card-text'>" . $comment["date"] . "</p>";
          echo '</div>'; // CARD BODY
          echo '</div>'; // CARD
        }
      }
      
      echo '<form action="addComment.php" method="post">
              <div class="container-fluid">
                <label for="comment">Insert a comment:</label>
                <textarea class="form-control" rows="5" id="comment" name="text" style="resize:none" placeholder="Type here..."></textarea>
                <input type="hidden" name="idPost" value="' . $row["idPost"] . '">';
      if(isset($_SESSION["idUser"])) {
        echo '<button type="submit" class="btn btn-primary">Post</button>';
      } else {
        echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Post</button>';
      }
                
      echo    '</div>
            </form>';
      echo '</div>'; // CARD BODY
      echo '</div>'; // CARD
      echo '</div>'; // DIV

      echo '<div class="modal" id="modal'. $user[0]["idUser"] .'">';
      ?>
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <h4 class="modal-title">User profile:</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
      <?php
          echo '<div class="modal-body">
              <div class="card mb-4">
                <div class="card-body text-center">
                  <img src="'.$user[0]['image_path'].'" alt="avatar" onerror="this.src=\'../images/default.jpg\';"
                    class="rounded-circle img-fluid" style="width: 150px">
                  <h5 class="my-3">'.$user[0]['Username'].'</h5>
                  <p class="text-muted mb-1">Bio:</p>
                  <p class="text-muted mb-4">'.$user[0]['Bio'].'</p>
                  <div class="row align-items-start">
                    <div class="col">
                      <p class="text-muted mb-4">Follower: '.$user[0]['Follower'].'</p>
                    </div>
                    <div class="col">
                      <p class="text-muted mb-4">Followed: '.$user[0]['Followed'].'</p>
                    </div>
                  </div>
                  <div class="d-flex justify-content-center mb-2">
                      <form action="userpage.php" method="POST">
                        <input type="hidden" name="idUser" value="'.$user[0]['idUser'].'" />
                        <input type="hidden" name="name" value="'.$user[0]['Username'].'" />
                        <button type="submit" class="btn btn-primary align-self-end m-2">See profile</button>
                      </form>';
          if(isset($_SESSION["idUser"])){
            $query = DBHandler::getPDO()->prepare('SELECT * from follow WHERE follower =' . $_SESSION["idUser"] . ' AND followed =' . $user[0]["idUser"] . ';');
            $query->execute();
            
            if($_SESSION["idUser"] != $user[0]["idUser"]){
              if($query->rowCount() > 0){
                echo'<button id="follow' . $row["idPost"] . '" class="btn btn-secondary align-self-end m-2" onclick="addFollow(\'' . $_SESSION["idUser"] . '\',\'' . $user[0]["idUser"] . '\',\'' . $row["idPost"] . '\')">Followed</button>';
              }else{
                echo'<button id="follow' . $row["idPost"] . '" class="btn btn-primary align-self-end m-2" onclick="addFollow(\'' . $_SESSION["idUser"] . '\',\'' . $user[0]["idUser"] . '\',\'' . $row["idPost"] . '\')">Follow</button>';
              }
            }
          }else{
            echo'<button type="button" class="btn btn-primary align-self-end m-2" disabled>Follow</button>';
          }
            echo '</div>
                </div>
              </div>
            </div>';
      ?>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>

          </div>
        </div>
      </div>
    <?php
    }
  }else{
    echo '<div class="container-fluid d-flex">';
    echo '<div class="container-fluid d-flex flex-column">
    <div class="h3 mx-auto pt-5"> <?php echo $name; ?> There are no posts here... <i class="bi bi-emoji-frown"></i></div>';
  }

    $prep = DBHandler::getPDO()->prepare('SELECT count(idpost) as totalposts FROM post WHERE idUser IN (SELECT followed FROM follow WHERE follower = :idUser);');
    $prep->bindParam(':idUser', $_SESSION["idUser"], PDO::PARAM_INT);
    $prep->execute();
    $totalposts = $prep->fetch(PDO::FETCH_ASSOC);
    $totalPosts = $totalposts['totalposts'];
    $totalPages = ceil($totalPosts / $postsPerPage);

    $visiblePages = 5;

    $startPage = max(1, $page - floor($visiblePages / 2));
    $endPage = min($totalPages, $startPage + $visiblePages - 1);

    if ($endPage - $startPage + 1 < $visiblePages) {
        $startPage = max(1, $endPage - $visiblePages + 1);
    }

    echo '<ul class="pagination mx-auto">';
    if ($page > 1) {
        echo '<li class="page-item"><a class="btn btn-primary" href="?page=' . ($page - 1) . '">Previous</a></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $page) {
            echo '<li class="page-item active"><a class="btn btn-primary disabled">' . $i . '</a></li>';
        } else {
            echo '<li class="page-item"><a class="btn btn-primary" href="?page=' . $i . '">' . $i . '</a></li>';
        }
    }

    if ($page < $totalPages) {
        echo '<li class="page-item"><a class="btn btn-primary" href="?page=' . ($page + 1) . '">Next</a></li>';
    }
    echo '</ul>';
  ?>
</div>

<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Comment error.</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        You need to be logged in to insert a comment. You can log in <a href="loginForm.php">here</a>.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</div>
