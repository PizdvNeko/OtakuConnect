<?php
$prep = DBHandler::getPDO()->prepare('SELECT * from user WHERE iduser=:idUser;');
$prep->bindParam('idUser', $_SESSION["idUser"], PDO::PARAM_INT);
$prep->execute();
$rows = $prep->fetchAll();

?>
<section style="background-color: #eee;">
  <div class="container py-5">

    <div class="row">
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
            <h5 class="my-3">This is how you'll be seen by others:</h5>
            <img src="<?php echo $rows[0]['image_path']; ?>" alt="avatar" onerror="this.src='../images/default.jpg';"
              class="rounded-circle img-fluid" style="width: 150px">
            <h5 class="my-3"><?php echo $rows[0]['Username']; ?></h5>
            <p class="text-muted mb-1">Bio:</p>
            <p class="text-muted mb-4"><?php echo $rows[0]['Bio']; ?></p>
            <div class="row align-items-start">
              <div class="col">
              <p class="text-muted mb-4">Follower: <?php echo $rows[0]['Follower']; ?></p>
              </div>
              <div class="col">
              <p class="text-muted mb-4">Followed: <?php echo $rows[0]['Followed']; ?></p>
              </div>
            </div>
            <div class="d-flex justify-content-center mb-2">
                <a class="btn btn-primary align-self-end m-2" href="userOptionModify.php"><i class="bi bi-pencil-fill"></i>Modify profile</a>
            </div>
          </div>
        </div>

      </div>
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Username</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rows[0]['Username']; ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rows[0]['Email']; ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Bio</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rows[0]['Bio']; ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Follower</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rows[0]['Follower']; ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Followed</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rows[0]['Followed']; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <a href="disconnect.php" class="btn btn-dark">Disconnect</a>
    </div>
  </div>
</section>
