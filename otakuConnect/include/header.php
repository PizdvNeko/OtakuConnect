<!DOCTYPE html>
<html lang="en">
<head>
  <title>OtakuConnect</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="script.js"></script>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<a href="firstpage.php" class="text-decoration-none">
  <div class="p-5 text-black text-center" style="background-image: url('../images/bello.jpg'); background-position: center; background-repeat: no-repeat; background-size: cover; background-color: #8f00ff;">
    <h1>OtakuConnect</h1>
    <p>Where you can and <mark>have</mark> to discuss about anime!</p> 
  </div>
</a>

<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="firstpage.php">OtakuConnect</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="followerPage.php">Followers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="animePage.php">Anime</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="communityPage.php">Community</a>
        </li>
      </ul>
      <?php 
        include 'DBhandler.php';
        if(isset($_SESSION["idUser"])): 
          $prep = DBHandler::getPDO()->prepare('SELECT * from user WHERE iduser=:idUser;');
          $prep->bindParam('idUser', $_SESSION["idUser"], PDO::PARAM_INT);
          $prep->execute();
          $rows = $prep->fetchAll();
          $name = $rows[0]['Username'];
      ?>
      <div class="d-flex align-items-center">
        <span class="navbar-text me-2">Hi, <?php echo $name; ?></span>
        <img src="<?php echo $rows[0]['image_path']; ?>" alt="Not Found" class="rounded-pill" style="width:40px;">
        <div class="dropdown ms-2">
          <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"></button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <form action="userpage.php" method="POST">
                <input type="hidden" name="idUser" value="<?php echo $rows[0]['idUser']; ?>" />
                <input type="hidden" name="name" value="<?php echo $rows[0]['Username']; ?>" />
                <button type="submit" class="dropdown-item">Profile</button>
              </form>
            </li>
            <li><hr class="dropdown-divider"></hr></li>
            <li><a class="dropdown-item" href="userOption.php">Options</a></li>
          </ul>
        </div>
      </div>
      <?php else: ?>
      <a class="btn btn-primary" href="loginForm.php">Log In</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<?php
  if(isset($_SESSION["Warning-comment"]) && $_SESSION["Warning-comment"]):
?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Warning!</strong> You cannot comment anything! Please, insert a text.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php
  $_SESSION["Warning-comment"] = false;
endif;

if(isset($_SESSION["Warning-post"]) && $_SESSION["Warning-post"]):
?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Warning!</strong> You cannot post blank spot! Please, insert a text.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php
  $_SESSION["Warning-post"] = false;
endif;

if(isset($_SESSION["Warning-image"]) && $_SESSION["Warning-image"]):
?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Warning!</strong> File error! Only JPG, JPEG, PNG & GIF files are allowed.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php
  $_SESSION["Warning-image"] = false;
endif;
?>