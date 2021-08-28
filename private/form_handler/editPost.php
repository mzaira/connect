<?php
require '../classes/Config.php';

//to get post id
if (isset($_GET['post_id'])) {
	$post_id = $_GET['post_id'];
}

//to get the bootbox result
if (isset($_POST['result'])) {

	$text = $_POST['result'];

	if ($text != NULL) {

        DB::query('UPDATE posts SET body=:body WHERE id=:postid', array(':body'=>$text, ':postid'=>$post_id));
	}
}
