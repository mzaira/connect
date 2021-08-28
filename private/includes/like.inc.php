<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Like</title>

	<script src="https://kit.fontawesome.com/035ed2373e.js" crossorigin="anonymous"></script>

	<style type="text/css">
		body {
			height: auto;
			width: 100%;
			background-color: transparent;
			cursor: default;
			box-sizing: border-box;
		}

		form {
			height: 100%;
			width: 100%;
		}

		button {
			background-color: transparent;
			border: none;
			font-size: 0.9rem;
		}

		.likeContainer {
			display: flex;
			flex-direction: row;
			align-items: center;
			font-size: 0.9rem;
		}

		.likeContainer:hover,
		.likeContainer button:hover {
			color: #FE3A3A;
		}

		.liked button,
		.liked .like_value {
			color: #FE3A3A;
		}

		button:focus {
			outline: none;
		}
	</style>
</head>

<body>

	<?php
	//to call config, and classes: user, post, notification
	require "../classes/config.php";
	require "../classes/login.php";
	require "../classes/user.php";
	require "../classes/post.php";

	if (Login::isLoggedIn()) {

		//Get id of post
		if (isset($_GET['post_id'])) {
			$post_id = $_GET['post_id'];
		}

		$post = DB::query(
			'SELECT * FROM posts WHERE id=:postid',
			array(':postid' => $post_id)
		);

		foreach ($post as $row) {
			$total_likes = $row['likes'];
		}
	} else {
		header('Location: register.php');
	}

	//Like button
	if (isset($_POST['like_button'])) {
		$total_likes++;

		DB::query(
			'UPDATE posts SET likes=:likes WHERE id=:likeid',
			array(':likes' => $total_likes, ':likeid' => $post_id)
		);

		DB::query(
			'INSERT INTO likes VALUES ("", :postid, :userid)',
			array(':postid' => $post_id, ':userid' => Login::isLoggedIn())
		);

		//Insert Notification
		/*if ($postedBy != $userLoggedIn) {
			$notification = new Notification($con, $userLoggedIn);
			$notification->insertNotification($post_id, $user_liked, "like", $userLoggedIn);
		}*/
	}
	//Unlike button
	if (isset($_POST['unlike_button'])) {
		$total_likes--;

		DB::query(
			'UPDATE posts SET likes=:likes WHERE id=:likeid',
			array(':likes' => $total_likes, ':likeid' => $post_id)
		);

		DB::query(
			'DELETE FROM likes WHERE user_id=:userid AND post_id=:postid',
			array(':userid' => Login::isLoggedIn(), ':postid' => $post_id)
		);
	}

	//Check for previous likes
	$num_rows = DB::rows(
		'SELECT COUNT(*) FROM likes WHERE user_id=:userid AND post_id=:postid',
		array(':userid' => Login::isLoggedIn(), ':postid' => $post_id)
	);

	if ($num_rows > 0) {
		echo '<form action="like.inc.php?post_id=' . $post_id . '" method="POST">
				<div class="likeContainer liked">
					<button type="submit" class="comment_like" name="unlike_button"><i class="fas fa-heart"></i></button>
					<div class="like_value"> 
					' . $total_likes . ' 
					</div>
				</div>
			</form>
		';
	} else {
		echo '<form action="like.inc.php?post_id=' . $post_id . '" method="POST">
				<div class="likeContainer">
					<button type="submit" class="comment_like" name="like_button"><i class="far fa-heart"></i></button>
					<div class="like_value">
					' . $total_likes . '
					</div>
				</div>
			</form>
		';
	}
	?>

</body>

</html>