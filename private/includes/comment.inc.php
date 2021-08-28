<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment</title>

    <script src="https://kit.fontawesome.com/035ed2373e.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style type="text/css">
        body {
            padding-right: 10px;
            height: auto;
            width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        form {
            width: 100%;
            height: 40px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        textarea {
            padding: 5px 10px;
            height: 100%;
            width: 80%;
            resize: none;
            background-color: transparent;
            border: none;
            border-bottom: 1px #767676 solid;
            font-family: 'Montserrat', sans-serif;
        }

        .submitComment {
            height: 100%;
            margin-left: 10px;
            width: 18%;
            background-color: #752FFF;
            color: #F9F9F9;
            border: none;
            border-radius: 30px;
        }

        textarea:focus,
        .submitComment:focus {
            outline: none;
        }

        img {
            border-radius: 50px;
            width: 30px;
            height: 30px;
        }

        .comment_section {
            width: 100%;
            height: auto;
            margin: 10px 0;
            border-bottom: 1px #767676 solid;
        }

        .commentHeader {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
        }

        a {
            margin-right: 10px;
            text-decoration: none;
        }

        .info {
            font-size: 0.9rem;
            color: #767676;
        }

        .commentBody {
            padding: 5px 0;
            padding-left: 40px;
            font-size: 0.9rem;
        }

        .noComment {
            color: #cecece;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            height: 110px;
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

    //to get the userLoggedin
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

        if (isset($_POST['postComment' . $post_id])) {

            $post_body = $_POST['post_body'];

            DB::query(
                'INSERT INTO comments VALUES ("", :body, :posted_by, 0, NOW(), :postid)',
                array(':body' => $post_body, ':posted_by' => Login::isLoggedIn(), ':postid' => $post_id)
            );

            /*if ($posted_to != $userLoggedIn) {
                $notification = new Notification($con, $userLoggedIn);
                $notification->insertNotification($post_id, $posted_to, "comment", $userLoggedIn);
            }

            if ($posted_to != 'none' && $posted_to != $userLoggedIn) {
                $notification = new Notification($con, $userLoggedIn);
                $notification->insertNotification($post_id, $posted_to, "profile_comment", $userLoggedIn);
            }*/


            /*$get_commenters = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id'");
            $notified_users = array();
            while ($row = mysqli_fetch_array($get_commenters)) {

                if (
                    $row['posted_by'] != $posted_to && $row['posted_by'] != $posted_to
                    && $row['posted_by'] != $id && !in_array($row['posted_by'], $notified_users)
                ) {

                    $notification = new Notification($con, $userLoggedIn);
                $notification->insertNotification($post_id, $row['posted_by'], "comment_non_owner", $userLoggedIn);

                array_push($notified_users, $row['posted_by']);
                }
            }*/
        }
    } else {
        header('Location: register.php');
    }

    ?>
    <script>
        function toggle() {
            var element = document.getElementById("comment_section");

            if (element.style.display == "block") {
                element.style.display = "none";
            } else {
                element.style.display = "block";
            }
        }
    </script>

    <form action="comment.inc.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
        <textarea name="post_body" placeholder="Comment.."></textarea>
        <input type="submit" name="postComment<?php echo $post_id; ?>" value="Comment" class="submitComment">
    </form>

    <!-- Load comments -->
    <?php

    $count = DB::rows('SELECT COUNT(*) FROM comments WHERE post_id=:postid', array(':postid' => $post_id));
    if ($count != 0) {

        $comment_query = DB::query('SELECT * FROM comments WHERE post_id=:postid ORDER BY id DESC', array(':postid' => $post_id));

        foreach ($comment_query as $comment) {

            $comment_body = $comment['body'];
            $posted_by = $comment['posted_by'];
            $date_added = $comment['posted_on'];

            //Timeframe
            $date_time_now = date("Y-m-d H:i:s");
            $start_date = new DateTime($date_added); //Time of post
            $end_date = new DateTime($date_time_now); //Current time
            $interval = $start_date->diff($end_date); //Difference between dates 
            if ($interval->y >= 1) {
                $time_message = $interval->y . "y";
            } else if ($interval->m >= 1) {
                $dateObj   = DateTime::createFromFormat('!m', $interval->m);
                $monthName = $dateObj->format('M');
                $time_message = $monthName;
            } else if ($interval->d >= 1) {
                $time_message = $interval->d . "d";
            } else if ($interval->h >= 1) {
                $time_message = $interval->h . "h";
            } else if ($interval->i >= 1) {
                $time_message = $interval->i . "m";
            } else {
                $time_message = $interval->s . "s";
            }


    ?>
            <div class="comment_section">
                <div class="commentHeader">
                    <a href="../../profile.php?id=<?php echo $posted_by;?>" target="_parent"><img src="../../<?php echo User::profileImage($posted_by); ?>" title="<?php echo $posted_by; ?>"></a>
                    <a href="../../profile.php?id=<?php echo $posted_by;?>" target="_parent"><?php echo User::name($posted_by); ?></a>
                    <a href="../../profile.php?id=<?php echo $posted_by;?>" target="_parent" class="info">@<?php echo User::username($posted_by); ?></a>
                    <a class="date info"> <?php echo $time_message . "</a>" . "</div><p class='commentBody'>" . $comment_body;
                                            "</p>" ?>
                </div>
        <?php

        }
    } else {
        echo "<div class='noComment'>No Comments to Show!</div>";
    }

        ?>

</body>

</html>