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
    $postsPerPage = 10;
    // Determine current page
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $start = ($page - 1) * $postsPerPage;

    $prep = DBHandler::getPDO()->prepare('SELECT * FROM post WHERE idUser = :idUser ORDER BY date DESC LIMIT :start, :limit;');
    $prep->bindParam(':idUser', $_POST["idUser"], PDO::PARAM_STR);
    $prep->bindParam(':start', $start, PDO::PARAM_INT);
    $prep->bindParam(':limit', $postsPerPage, PDO::PARAM_INT);
    $prep->execute();
    if($prep->rowCount() > 0){
?>

<div class="container-fluid">
    <div class="container mt-5">
        <h1>User Followers and Followed</h1>

        <div class="row">
            <div class="col-md-6">
                <h2>Followers</h2>
                <ul class="list-group">
                    <?php
                    $followersQuery = "
                        SELECT user.*
                        FROM user 
                        INNER JOIN follow ON user.idUser = follow.Follower
                        WHERE follow.Followed = :userId ORDER BY user.Username";
                    $stmt = DBHandler::getPDO()->prepare($followersQuery);
                    $stmt->execute(['userId' => $_POST["idUser"]]);
                    $followers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $followedQuery = "
                        SELECT user.*
                        FROM user 
                        INNER JOIN follow ON user.idUser = follow.followed
                        WHERE follow.follower = :userId ORDER BY user.Username";
                    $stmt = DBHandler::getPDO()->prepare($followedQuery);
                    $stmt->execute(['userId' => $_POST["idUser"]]);
                    $followed = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($followers) > 0) {
                        foreach ($followers as $follower) {
                            echo '<li class="list-group-item">
                            <a data-bs-toggle="modal" data-bs-target="#modal'.$follower["idUser"].'">
                            <img src="'. $follower["image_path"] .'" alt="Not Found" onerror="this.src=\'../images/default.jpg\';" style="width:40px;" class="rounded-pill"> ' 
                            . htmlspecialchars($follower['Username']) . 
                            '</a>
                            </li>';

                            echo '<div class="modal" id="modal'. $follower["idUser"] .'">';
                            echo '<div class="modal-dialog">
                                  <div class="modal-content">
                        
                                    <div class="modal-header">
                                      <h4 class="modal-title">User profile:</h4>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                  <div class="modal-body">
                                    <div class="card mb-4">
                                      <div class="card-body text-center">
                                        <img src="'.$follower['image_path'].'" alt="avatar" onerror="this.src=\'../images/default.jpg\';"
                                          class="rounded-circle img-fluid" style="width: 150px">
                                        <h5 class="my-3">'.$follower['Username'].'</h5>
                                        <p class="text-muted mb-1">Bio:</p>
                                        <p class="text-muted mb-4">'.$follower['Bio'].'</p>
                                        <div class="row align-items-start">
                                          <div class="col">
                                            <p class="text-muted mb-4">Follower: '.$follower['Follower'].'</p>
                                          </div>
                                          <div class="col">
                                            <p class="text-muted mb-4">Followed: '.$follower['Followed'].'</p>
                                          </div>
                                        </div>
                                        <div class="d-flex justify-content-center mb-2">
                                            <form action="userpage.php" method="POST">
                                              <input type="hidden" name="idUser" value="'.$follower['idUser'].'" />
                                              <input type="hidden" name="name" value="'.$follower['Username'].'" />
                                              <button type="submit" class="btn btn-primary align-self-end m-2">See profile</button>
                                            </form>';
                                if(isset($_SESSION["idUser"])){
                                  $query = DBHandler::getPDO()->prepare('SELECT * from follow WHERE follower =' . $_SESSION["idUser"] . ' AND followed =' . $follower["idUser"] . ';');
                                  $query->execute();
                                  
                                  if($_SESSION["idUser"] != $follower["idUser"]){
                                    if($query->rowCount() > 0){
                                      echo'<button id="follow' . $follower["idUser"] . '" class="btn btn-secondary align-self-end m-2" onclick="addFollow(\'' . $_SESSION["idUser"] . '\',\'' . $follower["idUser"] . '\',\'' . $follower["idUser"] . '\')">Followed</button>';
                                    }else{
                                      echo'<button id="follow' . $follower["idUser"] . '" class="btn btn-primary align-self-end m-2" onclick="addFollow(\'' . $_SESSION["idUser"] . '\',\'' . $follower["idUser"] . '\',\'' . $follower["idUser"] . '\')">Follow</button>';
                                    }
                                  }
                                }else{
                                  echo'<button type="button" class="btn btn-primary align-self-end m-2" disabled>Follow</button>';
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
                        echo '<li class="list-group-item">No followers found.</li>';
                    }
                    ?>
                </ul>
            </div>

            <div class="col-md-6">
                <h2>Followed Users</h2>
                <ul class="list-group">
                    <?php
                    if (count($followed) > 0) {
                        foreach ($followed as $follow) {
                            echo '<li class="list-group-item">
                            <a data-bs-toggle="modal" data-bs-target="#modal'.$follow["idUser"].'">
                            <img src="'. $follow["image_path"] .'" alt="Not Found" onerror="this.src=\'../images/default.jpg\';" style="width:40px;" class="rounded-pill"> ' 
                            . htmlspecialchars($follow['Username']) . 
                            '</a>
                            </li>';

                            echo '<div class="modal" id="modal'. $follow["idUser"] .'">';
                            echo '<div class="modal-dialog">
                                  <div class="modal-content">
                        
                                    <div class="modal-header">
                                      <h4 class="modal-title">User profile:</h4>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                  <div class="modal-body">
                                    <div class="card mb-4">
                                      <div class="card-body text-center">
                                        <img src="'.$follow['image_path'].'" alt="avatar" onerror="this.src=\'../images/default.jpg\';"
                                          class="rounded-circle img-fluid" style="width: 150px">
                                        <h5 class="my-3">'.$follow['Username'].'</h5>
                                        <p class="text-muted mb-1">Bio:</p>
                                        <p class="text-muted mb-4">'.$follow['Bio'].'</p>
                                        <div class="row align-items-start">
                                          <div class="col">
                                            <p class="text-muted mb-4">Follower: '.$follow['Follower'].'</p>
                                          </div>
                                          <div class="col">
                                            <p class="text-muted mb-4">Followed: '.$follow['Followed'].'</p>
                                          </div>
                                        </div>
                                        <div class="d-flex justify-content-center mb-2">
                                            <form action="userpage.php" method="POST">
                                              <input type="hidden" name="idUser" value="'.$follow['idUser'].'" />
                                              <input type="hidden" name="name" value="'.$follow['Username'].'" />
                                              <button type="submit" class="btn btn-primary align-self-end m-2">See profile</button>
                                            </form>';
                                if(isset($_SESSION["idUser"])){
                                  $query = DBHandler::getPDO()->prepare('SELECT * from follow WHERE follower =' . $_SESSION["idUser"] . ' AND followed =' . $follow["idUser"] . ';');
                                  $query->execute();
                                  
                                  if($_SESSION["idUser"] != $follow["idUser"]){
                                    if($query->rowCount() > 0){
                                      echo'<button id="follow' . $follow["idUser"] . '" class="btn btn-secondary align-self-end m-2" onclick="addFollow(\'' . $_SESSION["idUser"] . '\',\'' . $follow["idUser"] . '\',\'' . $follow["idUser"] . '\')">Followed</button>';
                                    }else{
                                      echo'<button id="follow' . $follow["idUser"] . '" class="btn btn-primary align-self-end m-2" onclick="addFollow(\'' . $_SESSION["idUser"] . '\',\'' . $follow["idUser"] . '\',\'' . $follow["idUser"] . '\')">Follow</button>';
                                    }
                                  }
                                }else{
                                  echo'<button type="button" class="btn btn-primary align-self-end m-2" disabled>Follow</button>';
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
                        echo '<li class="list-group-item">No followed users found.</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

<div class="container">
<div class="h3 mx-auto pt-5"> <?php echo $_POST["name"]; ?> posts: </div>
<div class="row row-cols-1 row-cols-md-2 g-4">

<?php
    foreach($prep->fetchAll() as $row) {
      $query = DBHandler::getPDO()->prepare('SELECT * from user where iduser =' . $row["idUser"] . ';');
      $query->execute();
      $username = $query->fetchAll();
?>
<div class="col">
    <div class="card my-3 mx-auto" style="max-width: 100%;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title float-start">
<?php
    echo    '<img src="'. $username[0]["image_path"] .'" alt="Not Found" onerror="this.src=\'../images/default.jpg\';" style="width:40px;" class="rounded-pill">
            ' . $username[0]["Username"] . ' posted: </h3>';
            if(isset($_SESSION["idUser"]) && $row["idCommunity"] != ""){  
              $query = DBHandler::getPDO()->prepare('SELECT * from community where idCommunity =' . $row["idCommunity"] . ';');
              $query->execute();
              $community = $query->fetch(PDO::FETCH_ASSOC);
              echo  '<a class="card-link float-end">Community: '. $community["Name"] .'</a>
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
                <i class="bi bi-heart" id="' . $row["idPost"] . '"> ' . $row["Likes"] . '</i></button>';
        } else {
          echo '<button class="btn btn-danger" onclick="addLike(\'' . $row["idPost"] . '\', \'' . $_SESSION["idUser"] . '\')">
                <i class="bi bi-heart-fill" id="' . $row["idPost"] . '"> ' . $row["Likes"] . '</i></button>';
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
    }
    
    //end first if
    }else{
      echo '<div class="container-fluid d-flex">';
      echo '<div class="container-fluid d-flex flex-column">
      <div class="h3 mx-auto pt-5"> <?php echo $name; ?> There are no posts here... <i class="bi bi-emoji-frown"></i></div>';
    }
    echo '</div>';
    

    //PAGES
    $prep = DBHandler::getPDO()->prepare('SELECT count(idpost) as totalposts FROM post WHERE idUser = :idUser;');
    $prep->bindParam(':idUser', $_SESSION["idUser"], PDO::PARAM_STR);
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

    echo '<ul class="pagination justify-content-center">';
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
  
  
    </div>
  </div>
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