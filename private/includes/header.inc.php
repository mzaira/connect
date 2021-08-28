<?php

//require
require 'private/classes/Config.php';
require 'private/classes/Login.php';
require 'private/classes/User.php';
require 'private/classes/Follower.php';
require 'private/classes/Post.php';

date_default_timezone_set('Europe/London');

//check if the user is logged in
if (Login::isLoggedIn()) {
    $userid = Login::isLoggedIn();

    if (isset($_POST['post'])) {
        $postbody = $_POST['postbody'];
        Post::submitPost($postbody, $userid);
    }

    //get the profile_pic and full name of the user
    $profile_pic = User::profileImage($userid);
    $profileName = User::name($userid);

    if(isset($_GET['id'])){
        $id = $_GET['id'];

        $pagename = User::name($id).' | Connect';
    }
    
} else {
    header('Location: register.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!---Change the title of the webpage by echoing the $pagename-->
    <title><?php echo $pagename; ?></title>
    <link rel="shortcut icon" href="assets/img/others/logoI.ico" />

    <!--EXTERNAL RESOURCES: font, jQuery, fontAwesome, sweetAlert-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://kit.fontawesome.com/035ed2373e.js" crossorigin="anonymous"></script>

    <!--CSS SOURCE: main.css-->
    <link href="assets/scss/main.css" type="text/css" rel="stylesheet">

    <!--JAVASCRIPT: mainFunction.js-->
    <script src="private/js/mainFunctions.js"></script>
</head>