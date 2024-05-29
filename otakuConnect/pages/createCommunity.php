<div class="d-flex flex-column container p-5 my-5 bg-dark text-white rounded-4 w-75 shadow ">
    <div class="container mt-5">
        <h1>Crea una nuova community</h1>
        <form action="addCommunity.php" method="post" enctype="multipart/form-data">
            <div class="form-group mb-3 mt-3">
                <label for="name">Nome:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group mb-3 mt-3">
                <label for="description">Descrizione:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group mb-3 mt-3">
                <label for="file">Choose an image:</label>
                <input type="file" id="file" name="file">
            </div>
            <input type="hidden" name="idCreator" value="<?php echo $_SESSION["idUser"]; ?>" />
            <button type="submit" class="btn btn-primary">Crea Community</button>
        </form>
    </div>
</div>