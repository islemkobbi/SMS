<?php
include 'config.php';

// Get reference to uploaded image
$image_file = $_FILES["image"];

// Image not defined, let's exit
if (!isset($image_file)) {
    die('No file uploaded.');
}


// Move the temp image file to the images/ directory
move_uploaded_file(
    // Temp image location
    $image_file["tmp_name"],

    // New image location, __DIR__ is the location of the current PHP file
    __DIR__ . "/../assets/braking_news.png"
);

$br = 1;
$sql = "UPDATE _admin SET breaking = $br";
$result = mysqli_query($conn, $sql);

header("location: ../admin.php");
