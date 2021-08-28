<?php
class Comment
{
    public static function submitComment($body, $postid)
    {
        $body = strip_tags($body);
        $body = str_replace('\r\n', '\n', $body);
        $body = nl2br($body);
        $check_empty = preg_replace('/\s+/', '', $body);

        if ($check_empty != "") {
            $loggedinUser = Login::isLoggedIn();

            if (!DB::query('SELECT id FROM posts WHERE id=:postid', array(':postid' => $postid))) {
                echo "invalid post id";
            } else {
                DB::query(
                    'INSERT INTO comments VALUES("", :body, :postedby, 0, NOW(), :postid)',
                    array(':body' => $body, ':postedby' => $loggedinUser, ':postid' => $postid)
                );
            }
        }
    }
    public static function commentIndex($postid)
    {
        $loggedinUser = Login::isLoggedIn();

        $dbcomment = DB::query('SELECT * FROM comments WHERE post_id=:postid ORDER BY id DESC', array(':postid' => $postid));
        $comment = "";

        foreach ($dbcomment as $c) {
            $comment .= '
            <div class="commentBody">
                <div class="wrapper">
                    <figure>
                        <img src="' . User::profileImage($loggedinUser) . '">
                    </figure>
                    <div class="body">
                        <h1>' . User::name($c['posted_by']) . '</h1>
                        <p>' . $c['body'] . '</p>
                    </div>
                    <button onclick="opencOption()"><i class="fas fa-ellipsis-h"></i></button>

                    <div onclick="closecOption()" id="commentOverlay" class="overlay"></div>
                    <div id="commentOption">
                        <form>
                            <button type="submit" name="edit' . $c['id'] . '"><i class="fas fa-pen fa-fw"></i></button>
                            <button type="submit" name="delete' . $c['id'] . '"><i class="fas fa-trash-alt fa-fw"></i></button>
                        </form>
                    </div>
                </div>
                <div class="buttons">
                    <p>Date</p>
                    <form action="index.php">
                        <button type="submit" name="cLike" class="likes">82 Likes</button>
                    </form>
                    <button class="reply" onclick="openReply()">30 Replies</button>
                </div>
            </div>
            
            <div id="nreplyContainer">
            <div class="replyBody">
                <figure>
                    <img src="assets/img/others/sample2.png">
                </figure>
                <form action="index.php" method="post">
                    <div class="input-group">
                        <textarea name="commentBody" value="" onkeyup="this.setAttribute("value", this.value);"></textarea>
                        <label>Reply</label>
                    </div>
                </form>
            </div>
        </div>';
        }

        return $comment;
    }
    public static function commentProfile($getid, $postid)
    {
        $loggedinUser = Login::isLoggedIn();

        $dbcomment = DB::query('SELECT * FROM comments WHERE post_id=:postid ORDER BY id DESC', array(':postid' => $postid));
        $comment = "";

        foreach ($dbcomment as $c) {
            $postDate = $c['posted_on'];
            $date = "";

            if (isset($_POST['clikes' . $c['id'] . ''])) {
                if (!DB::query('SELECT user_id FROM clikes WHERE comment_id=:commentid 
                AND user_id=:userid', array(':commentid' => $c['id'], ':userid' => $loggedinUser))) {

                    DB::query('UPDATE comments SET likes = likes+1 WHERE id=:commentid', array(':commentid' => $c['id']));
                    DB::query('INSERT INTO clikes VALUES("", :commentid, :userid)', array(':commentid' => $c['id'], ':userid' => $loggedinUser));
                    header('Location: profile.php?id=' . $getid . '');
                } else {
                    DB::query('UPDATE comments SET likes = likes-1 WHERE id=:commentid', array(':commentid' => $c['id']));
                    DB::query('DELETE FROM clikes WHERE comment_id=:commentid', array(':commentid' => $c['id']));
                    header('Location: profile.php?id=' . $getid . '');
                }
            }

            $todayDate = date("Y-m-d H:i:s");
            $startDate = new DateTime($postDate);
            $endDate = new DateTime($todayDate);
            $interval = $startDate->diff($endDate);

            if ($interval->y >= 1) {
                if ($interval->y == 1) {
                    $date = $interval->y . " year ago";
                } else {
                    $date = $interval->y . " years ago";
                }
            } else if ($interval->m >= 1) {
                if ($interval->m == 1) {
                    $date = $interval->m . " month ago";
                } else {
                    $date = $interval->m . " months ago";
                }
            } else if ($interval->d >= 1) {
                if ($interval->d == 1) {
                    $date = "Yesterday";
                } else {
                    $date = $interval->d . " days ago";
                }
            } else if ($interval->h >= 1) {
                if ($interval->h == 1) {
                    $date = $interval->h . " hour ago";
                } else {
                    $date = $interval->h . " hours ago";
                }
            } else if ($interval->i >= 1) {
                if ($interval->i == 1) {
                    $date = $interval->i . " minute ago";
                } else {
                    $date = $interval->i . " minutes ago";
                }
            } else if ($interval->s >= 1) {
                if ($interval->s == 1) {
                    $date = $interval->s . " second ago";
                } else {
                    $date = $interval->s . " seconds ago";
                }
            }

            $comment .= '
            <div class="commentBody">
                <div class="wrapper">
                    <figure>
                        <img src="' . User::profileImage($loggedinUser) . '">
                    </figure>
                    <div class="body">
                        <h1>' . User::name($c['posted_by']) . '</h1>
                        <p>' . $c['body'] . '</p>
                    </div>
                </div>
                <div class="buttons">
                <p>' . $c['posted_on'] . '</p>
                    <form action="profile.php?id=' . $getid . '" method="post">
                        <p>' . $date . '</p>
                        <button type="submit" name="clikes' . $c['id'] . '" class="likes">' . $c['likes'] . ' Likes</button>';
            if ($loggedinUser == $getid) {
                $comment .= '<form>
                        <button type="submit" name="delete' . $c['id'] . '"><i class="fas fa-trash-alt fa-fw"></i></button>
                    </form>';
            }
            $comment .= '</div>
            </div>';

            if (isset($_POST['delete' . $c['id'] . ''])) {
                if (DB::query(
                    'SELECT * FROM comments WHERE posted_by=:userid AND id=:commentid',
                    array(':userid' => $loggedinUser, ':commentid' => $c['id'])
                )) {
                    DB::query(
                        'DELETE FROM comments WHERE posted_by=:userid AND id=:commentid',
                        array(':userid' => $loggedinUser, ':commentid' => $c['id'])
                    );
                    DB::query('DELETE FROM clikes WHERE comment_id=:commentid', array(':commentid' => $c['id']));

                    header('Location: profile.php?id=' . $getid . '');
                }
            }
        }

        return $comment;
    }
}
