<div class="d-flex flex-column container p-5 my-5 bg-dark text-white rounded-4 w-75 shadow ">
    <div class="container-sm">
        <form action="login.php" method="post">
            <div class="mb-3 mt-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" placeholder="Enter username" name="username">
            </div>
            <div class="mb-3">
                <label for="pwd" class="form-label">Password:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pswd">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <br>
    <?php
        if(isset($_SESSION["Warning-notfound"])):
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Warning!</strong> <?php echo $_SESSION["Warning-notfound"];?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        $_SESSION["Warning-notfound"] = null;
        endif; 
    ?>

    <a class="align-self-center" href="signinForm.php">Don't have an account? Sign in</a>
</div>