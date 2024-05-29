<div class="container shadow-lg p-5 my-5 border d-flex flex-column">
    <p class="h3">Change username</p>
    <div class="container-fluid mt-3 border border-2 rounded-3 p-3" style="background-color:#ddd">
        <form action="modifyUser.php" method="post">
            <div class="mb-3 mt-3">
                <label for="username" class="form-label">New username:</label>
                <input type="text" class="form-control" id="username" placeholder="Enter username" name="username">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <br><br>
    <p class="h3">Change bio</p>
    <div class="container-fluid mt-3 border border-2 rounded-3 p-3" style="background-color:#ddd">
        <form action="modifyUser.php" method="post">
            <div class="mb-3 mt-3">
                <label for="bio" class="form-label">New bio:</label>
                <textarea class="form-control" id="bio" placeholder="Enter bio" name="bio" style="resize:none" maxlength="255"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <br><br>
    <p class="h3">Change Email</p>
    <div class="container-fluid mt-3 border border-2 rounded-3 p-3" style="background-color:#ddd">
        <form action="modifyUser.php" method="post">
            <div class="mb-3 mt-3">
                <label for="email" class="form-label">New email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <br><br>
    <p class="h3">Change profile picture</p>
    <div class="container-fluid mt-3 border border-2 rounded-3 p-3" style="background-color:#ddd">
        <form action="modifyUser.php" method="post" enctype="multipart/form-data">
            <div class="mb-3 mt-3">
                <label for="file">Choose new profile picture:</label><br>
                <input type="file" id="file" name="file">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <br><br>
    <p class="h3">Change password</p>
    <?php
    if(isset($_SESSION["Warning-oldpw"]) && $_SESSION["Warning-oldpw"]):
    ?>
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>Warning!</strong> The old password is wrong. Plese, correct and try again.
    </div>
    <?php
        $_SESSION["Warning-oldpw"] = false;
        endif;
        if(isset($_SESSION["Warning-newpw"]) && $_SESSION["Warning-newpw"]):
    ?>
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>Warning!</strong> The new password is wrong. Plese, correct and try again.
    </div>
    <?php
        $_SESSION["Warning-newpw"] = false;
        endif;
    ?>
    <div class="container-fluid mt-3 border border-2 rounded-3 p-3" style="background-color:#ddd">
        <form action="modifyUser.php" method="post">
            <div class="mb-3 mt-3">
                <label for="oldpw" class="form-label">Insert old password:</label>
                <input type="password" class="form-control" id="oldpw" placeholder="Old password" name="oldpw">
            </div>
            <div class="mb-3 mt-3">
                <label for="newpw" class="form-label">Insert new password:</label>
                <input type="password" class="form-control" id="newpw" placeholder="New password" name="newpw">
            </div>
            <div class="mb-3 mt-3">
                <label for="newpw2" class="form-label">Reinsert new password:</label>
                <input type="password" class="form-control" id="newpw2" placeholder="New password" name="newpw2">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
