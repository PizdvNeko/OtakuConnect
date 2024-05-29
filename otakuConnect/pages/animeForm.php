<div class="container shadow-lg p-5 my-5 border d-flex flex-column">
    <p class="h3">Add anime</p>
    <div class="container-fluid mt-3 border border-2 rounded-3 p-3" style="background-color:#ddd">
        <form action="addAnime.php" method="post" enctype="multipart/form-data">
            <div class="mb-3 mt-3">
                <label for="Title" class="form-label">Title:</label>
                <input type="text" class="form-control" id="Title" placeholder="Enter title" name="Title">
            </div>
            <div class="mb-3 mt-3">
                <label for="Episodes" class="form-label">Episodes:</label>
                <input type="text" class="form-control" id="Episodes" placeholder="Enter episodes" name="Episodes">
            </div>
            <div class="mb-3 mt-3">
                <label for="Description" class="form-label">Description:</label>
                <input type="text" class="form-control" id="Description" placeholder="Enter description" name="Description">
            </div>
            <div class="mb-3 mt-3">
                <label for="banner">Banner image:</label><br>
                <input type="file" id="banner" name="banner">
            </div>
            <div class="mb-3 mt-3">
                <label for="image">Anime image:</label><br>
                <input type="file" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
