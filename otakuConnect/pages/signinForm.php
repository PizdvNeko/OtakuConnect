<?php
    if(isset($_SESSION["Warning-username"]) && $_SESSION["Warning-username"]):
    ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Warning!</strong> Username already used, try another one.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
      $_SESSION["Warning-username"] = false;
    endif;
?>
<div class="d-flex flex-column container p-5 my-5 bg-dark text-white rounded-4 w-75 shadow ">
    <div class="container-sm">
        <form action="signin.php" method="post">
            <div class="mb-3 mt-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" placeholder="Enter username" name="username">
            </div>
            <div class="mb-3 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
            </div>
            <div class="mb-3">
                <label for="pwd" class="form-label">Password:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <a class="align-self-center" href="loginForm.php">Do you have an account? Log in</a>
</div>