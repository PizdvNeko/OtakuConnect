<?php
$title = $_POST['Title'];
$episodes = $_POST['Episodes'];
$description = $_POST['Description'];

// Percorsi per le immagini
if(basename($_FILES["banner"]["name"] == "")){
    $bannerImagePath = "";
}else{
    $folder = "../images/";
    $bannerImagePath = $folder . basename($_FILES["banner"]["name"]);
    $imageFileType = strtolower(pathinfo($bannerImagePath,PATHINFO_EXTENSION));
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        $_SESSION['Warning-image'] = true;
        header('Location:firstpage.php');
        exit();
    }
}
if(basename($_FILES["image"]["name"] == "")){
    $animeImagePath = "";
}else{
    $folder = "../images/";
    $animeImagePath = $folder . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($animeImagePath,PATHINFO_EXTENSION));
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        $_SESSION['Warning-image'] = true;
        header('Location:firstpage.php');
        exit();
    }
}

// Caricamento file immagine
move_uploaded_file($_FILES['banner']['tmp_name'], $bannerImagePath);
move_uploaded_file($_FILES['image']['tmp_name'], $animeImagePath);

// Inserimento dati nel database
$sql = "INSERT INTO anime (Name, Episodes, Description, banner_path, image_path) 
        VALUES (:Title, :Episodes, :Description, :BannerImagePath, :AnimeImagePath)";

$stmt = DBHandler::getPDO()->prepare($sql);
$stmt->bindParam(':Title', $title);
$stmt->bindParam(':Episodes', $episodes);
$stmt->bindParam(':Description', $description);
$stmt->bindParam(':BannerImagePath', $bannerImagePath);
$stmt->bindParam(':AnimeImagePath', $animeImagePath);

if ($stmt->execute()) {
    echo "Anime aggiunto con successo!";
    header('Location:animePage.php');
} else {
    echo "Errore durante l'inserimento dell'anime.";
    header('Location:addAnime.php');
}