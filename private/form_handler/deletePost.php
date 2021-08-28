<?php
require '../classes/Config.php';

//to get post id
if (isset($_GET['post_id'])) {
	$post_id = $_GET['post_id'];
}

//to get the bootbox result
if (isset($_POST['result'])) {

	if ($_POST['result'] == true) {
        DB::query('DELETE FROM posts WHERE id=:postid', array(':postid'=>$post_id));
	}
}
