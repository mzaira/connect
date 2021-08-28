<?php
class Post
{
    public static function submitPost($body)
    {
        $body = strip_tags($body);
        $body = str_replace('\r\n', '\n', $body);
        $body = nl2br($body);
        $check_empty = preg_replace('/\s+/', '', $body);

        if ($check_empty != "") {
            $loggedinUser = Login::isLoggedIn();

            DB::query(
                'INSERT INTO posts VALUES("", :postbody, "", NOW(), :userid, 0)',
                array(':postbody' => $body, ':userid' => $loggedinUser)
            );
        }
    }

    public static function submitPostImage($body, $image)
    {
        $body = strip_tags($body);
        $body = str_replace('\r\n', '\n', $body);
        $body = nl2br($body);
        $check_empty = preg_replace('/\s+/', '', $body);

        if ($check_empty != "") {
            $loggedinUser = Login::isLoggedIn();

            DB::query(
                'INSERT INTO posts VALUES("", :postbody, :photo , NOW(), :userid, 0)',
                array(':postbody' => $body, ':userid' => $loggedinUser, ':photo' => $image)
            );
        }
    }

    public static function likePost($postid, $liker)
    {
        if (!DB::query('SELECT user_id FROM likes WHERE post_id=:postid AND user_id=:userid', array(':postid' => $postid, ':userid' => $liker))) {

            DB::query('UPDATE posts SET likes = likes+1 WHERE id=:postid', array(':postid' => $postid));
            DB::query('INSERT INTO likes VALUES("", :postid, :userid)', array(':postid' => $postid, ':userid' => $liker));
        } else {
            DB::query('UPDATE posts SET likes = likes-1 WHERE id=:postid', array(':postid' => $postid));
            DB::query('DELETE FROM likes WHERE post_id=:postid', array(':postid' => $postid));
        }
    }
    public static function link_add($text)
    {

        $text = explode(" ", $text);
        $newstring = "";
        foreach ($text as $word) {
            if (substr($word, 0, 1) == "@") {
                $id = DB::query(
                    'SELECT id FROM users WHERE username =:username',
                    array(':username' => substr($word, 1))
                )[0]['id'];
                $newstring .= "<a href='profile.php?id=" . $id . "'>" . htmlspecialchars($word) . "</a> ";
            } else if (substr($word, 0, 1) == "#") {
                $newstring .= "<a href='topics.php?topic=" . substr($word, 1) . "'>" . htmlspecialchars($word) . "</a> ";
            } else {
                $newstring .= htmlspecialchars($word) . " ";
            }
        }
        return $newstring;
    }
    public static function profilePosts($data, $limit)
    {
        $page = $data['page'];
        $userPage = $data['profileId'];

        if ($page == 1) {
            $start = 0;
        } else {
            $start = ($page - 1) * $limit;
        }

        $posts = ""; //String to return

        $rows = DB::rows(
            'SELECT COUNT(*) FROM posts WHERE posts.user_id = :userid ORDER BY posts.id DESC',
            array(':userid' => $userPage)
        );

        if ($rows > 0) {

            $data_query = DB::query(
                'SELECT * FROM posts WHERE posts.user_id = :userid ORDER BY posts.id DESC',
                array(':userid' => $userPage)
            );

            $num_iterations = 0; //Number of results checked (not necasserily posted)
            $count = 1;

            foreach ($data_query as $row) {
                $postid = $row['id'];
                $userPost = $row['user_id'];
                $date_time = $row['posted_on'];

                if ($num_iterations++ < $start) {
                    continue;
                }

                //Once 10 posts have been loaded, break
                if ($count > $limit) {
                    break;
                } else {
                    $count++;
                }


?>
                <script>
                    function toggle<?php echo $postid; ?>() {

                        var target = $(event.target);
                        if (!target.is("a")) {
                            var element = document.getElementById("toggleComment<?php echo $postid; ?>");

                            if (element.style.display == "block")
                                element.style.display = "none";
                            else
                                element.style.display = "block";
                        }
                    }
                </script>
                <?php

                $comment_rows = DB::rows(
                    'SELECT COUNT(*) FROM comments WHERE post_id=:postid',
                    array(':postid' => $postid)
                );

                //Timeframe
                $date_time_now = date("Y-m-d H:i:s");
                $start_date = new DateTime($date_time); //Time of post
                $end_date = new DateTime($date_time_now); //Current time
                $interval = $start_date->diff($end_date); //Difference between dates 
                if ($interval->y >= 1) {
                    $date = $start_date->format('M d y');
                    $date = $date;
                } else if ($interval->m >= 1) {
                    $date = $start_date->format('M d');
                    $date = $date;
                } else if ($interval->d >= 1) {
                    $date = $interval->d . "d";
                } else if ($interval->h >= 1) {
                    $date = $interval->h . "h";
                } else if ($interval->i >= 1) {
                    $date = $interval->i . "m";
                } else {
                    if ($interval->s < 30) {
                        $date = "Just Now";
                    } else {
                        $date = $interval->s . "s";
                    }
                }

                if ($comment_rows > 0) {
                    $comment = "<p class='comment more'><i class='fas fa-comment'></i><span>$comment_rows</span></p>";
                } else {
                    $comment = "<p class='comment'><i class='far fa-comment'></i><span>$comment_rows</span></p>";
                }

                $posts .= '<div class="post">
                    <div class="postHeader">
                        <figure><img src="' . User::profileImage($userPost) . '"></figure>
                        <div class="name">
                            <a href="profile.php?id=' . $userPost . '">' . User::name($userPost) . '</a>
                            <p>@' . User::username($userPost) . '</p>
                        </div>';
                if (Login::isLoggedIn() == $userPost) {
                    $posts .= '
                                    <div id="postOption">
                                        <button class="delete_button" id="deletePost' . $postid . '"><i class="fas fa-trash-alt"></i></button>
                                        <button class="edit_button" id="editPost' . $postid . '"><i class="fas fa-edit"></i></button>
                                    </div>';
                }
                $posts .= '</div> <div class="postBody">';

                if ($row['photo'] != NULL) {
                    $posts .= '
                        <div class="images">
                            <figure>
                            <img src="' . $row['photo'] . '">
                            </figure>
                        </div>';
                }

                $posts .= '<p>' . self::link_add($row['body']) . '</p>
                
                </div>
                    <div class="buttons" onClick="javascript:toggle' . $postid . '()">  
                        <p >' . $date . '</p>
                        <form action="index.php" method="post">
                            ' . $comment . '
                            <iframe src="private/includes/like.inc.php?post_id=' . $postid . '" scrolling="no"></iframe>
                        </form>
                    </div>
                    <div class="postComment" id="toggleComment' . $postid . '" style="display:none;">
						<iframe src="private/includes/comment.inc.php?post_id=' . $postid . '" id="comment_iframe" frameborder="0"></iframe>
					</div>
                </div>';


                ?>
                <script>
                    $(document).ready(function() {

                        $('#deletePost<?php echo $postid; ?>').on('click', function() {
                            swal({
                                    title: "Are you sure?",
                                    text: "Once deleted, you will not be able to recover this imaginary file!",
                                    icon: "warning",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((willDelete) => {
                                    if (willDelete) {
                                        $.post("private/form_handler/deletePost.php?post_id=<?php echo $postid; ?>&result='true'", {
                                            result: true
                                        });
                                        swal("Your post has been deleted!", {
                                            icon: "success",
                                        }).then(function() {
                                            location.reload();
                                        });
                                    } else {
                                        swal("Your post is not deleted!");
                                    }

                                });
                        });


                        $('#editPost<?php echo $postid; ?>').on('click', function() {

                            swal("Edit post text", {
                                    content: "input",
                                })
                                .then((value) => {
                                    $.post("private/form_handler/editPost.php?post_id=<?php echo $postid; ?>", {
                                            result: value
                                        },
                                        location.reload());
                                });
                        });

                    });
                </script>
            <?php

            } //End while loop

            if ($count > $limit) {
                $posts .= "<div class='noMore'>
                <input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
                    <input type='hidden' class='noMorePosts' value='false'>
                </div>";
            } else {
                $posts .= "<div class='noMore'><input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'> No more posts to show! </p></div>";
            }
        }

        echo $posts;
    }

    public static function indexPosts($data, $limit)
    {
        $page = $data['page'];

        if ($page == 1) {
            $start = 0;
        } else {
            $start = ($page - 1) * $limit;
        }

        $posts = ""; //String to return

        $rows = DB::rows(
            'SELECT COUNT(*) FROM posts, follow WHERE posts.user_id = :userid OR (follow.friend_id = :userid AND posts.user_id = follow.user_id)',
            array(':userid' => Login::isLoggedIn())
        );

        if ($rows >= 0) {

            $data_query = DB::query(
                'SELECT * FROM posts, follow WHERE follow.user_id = :userid AND (posts.user_id = follow.user_id OR posts.user_id = follow.friend_id) ORDER BY posts.id DESC',
                array(':userid' => Login::isLoggedIn())
            );

            $num_iterations = 0; //Number of results checked (not necasserily posted)
            $count = 1;

            foreach ($data_query as $row) {
                
                $postid = $row['0'];
                $userPost = $row['4'];
                $date_time = $row['posted_on'];

                if ($num_iterations++ < $start) {
                    continue;
                }

                //Once 10 posts have been loaded, break
                if ($count > $limit) {
                    break;
                } else {
                    $count++;
                }
                
            ?>
                <script>
                    function toggle<?php echo $postid; ?>() {

                        var target = $(event.target);
                        if (!target.is("a")) {
                            var element = document.getElementById("toggleComment<?php echo $postid; ?>");

                            if (element.style.display == "block")
                                element.style.display = "none";
                            else
                                element.style.display = "block";
                        }
                    }
                </script>
                <?php

                $comment_rows = DB::rows(
                    'SELECT COUNT(*) FROM comments WHERE post_id=:postid',
                    array(':postid' => $postid)
                );

                //Timeframe
                $date_time_now = date("Y-m-d H:i:s");
                $start_date = new DateTime($date_time); //Time of post
                $end_date = new DateTime($date_time_now); //Current time
                $interval = $start_date->diff($end_date); //Difference between dates 
                if ($interval->y >= 1) {
                    $date = $start_date->format('M d y');
                    $date = $date;
                } else if ($interval->m >= 1) {
                    $date = $start_date->format('M d');
                    $date = $date;
                } else if ($interval->d >= 1) {
                    $date = $interval->d . "d";
                } else if ($interval->h >= 1) {
                    $date = $interval->h . "h";
                } else if ($interval->i >= 1) {
                    $date = $interval->i . "m";
                } else {
                    if ($interval->s < 30) {
                        $date = "Just Now";
                    } else {
                        $date = $interval->s . "s";
                    }
                }

                if ($comment_rows > 0) {
                    $comment = "<p class='comment more'><i class='fas fa-comment'></i><span>$comment_rows</span></p>";
                } else {
                    $comment = "<p class='comment'><i class='far fa-comment'></i><span>$comment_rows</span></p>";
                }

                $posts .= '<div class="post">
                    <div class="postHeader">
                        <figure><img src="' . User::profileImage($userPost) . '"></figure>
                        <div class="name">
                            <a href="profile.php?id=' . $userPost . '">' . User::name($userPost) . '</a>
                            <p>@' . User::username($userPost) . '</p>
                        </div>';
                if (Login::isLoggedIn() == $userPost) {
                    $posts .= '
                                    <div id="postOption">
                                        <button class="delete_button" id="deletePost' . $postid . '"><i class="fas fa-trash-alt"></i></button>
                                        <button class="edit_button" id="editPost' . $postid . '"><i class="fas fa-edit"></i></button>
                                    </div>';
                }
                $posts .= '</div> <div class="postBody">';

                if ($row['photo'] != NULL) {
                    $posts .= '
                        <div class="images">
                            <figure>
                            <img src="' . $row['photo'] . '">
                            </figure>
                        </div>';
                }

                $posts .= '<p>' . self::link_add($row['body']) . '</p>
                
                </div>
                    <div class="buttons" onClick="javascript:toggle' . $postid . '()">  
                        <p >' . $date . '</p>
                        <form action="index.php" method="post">
                            ' . $comment . '
                            <iframe src="private/includes/like.inc.php?post_id=' . $postid . '" scrolling="no"></iframe>
                        </form>
                    </div>
                    <div class="postComment" id="toggleComment' . $postid . '" style="display:none;">
						<iframe src="private/includes/comment.inc.php?post_id=' . $postid . '" id="comment_iframe" frameborder="0"></iframe>
					</div>
                </div>';


                ?>
                <script>
                    $(document).ready(function() {

                        $('#deletePost<?php echo $postid; ?>').on('click', function() {
                            swal({
                                    title: "Are you sure?",
                                    text: "Once deleted, you will not be able to recover this imaginary file!",
                                    icon: "warning",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((willDelete) => {
                                    if (willDelete) {
                                        $.post("private/form_handler/deletePost.php?post_id=<?php echo $postid; ?>&result='true'", {
                                            result: true
                                        });
                                        swal("Your post has been deleted!", {
                                            icon: "success",
                                        }).then(function() {
                                            location.reload();
                                        });
                                    } else {
                                        swal("Your post is not deleted!");
                                    }

                                });
                        });


                        $('#editPost<?php echo $postid; ?>').on('click', function() {

                            swal("Edit post text", {
                                    content: "input",
                                })
                                .then((value) => {
                                    $.post("private/form_handler/editPost.php?post_id=<?php echo $postid; ?>", {
                                            result: value
                                        },
                                        location.reload());
                                });
                        });

                    });
                </script>
<?php

            } //End while loop

            if ($count > $limit) {
                $posts .= "<div class='noMore'>
                <input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
                    <input type='hidden' class='noMorePosts' value='false'>
                </div>";
            } else {
                $posts .= "<div class='noMore'><input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'> No more posts to show! </p></div>";
            }
        }

        echo $posts;
    }
}
