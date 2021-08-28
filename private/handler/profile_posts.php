<?php
//INCLUDE: post
require '../classes/Config.php';
require '../classes/Login.php';
require '../classes/User.php';
require '../classes/Post.php';

$limit = 10; //Number of posts to be loaded per call

//display posts
Post::profilePosts($_REQUEST, $limit);
?>
