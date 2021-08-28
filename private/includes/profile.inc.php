<?php

$isFollowing = False;

if (Login::isLoggedIn()) {

    if (isset($_GET['id'])) {

        if (DB::query('SELECT id FROM users WHERE id=:id', array(':id' => $_GET['id']))) {

            $getid = $_GET['id'];
            $id = Login::isLoggedIn();
            $Name = User::name($getid);
            $username = User::username($getid);
            $profilePic = User::profileImage($getid);
            $profileCov = User::coverImage($getid);
            $page = basename($_SERVER['REQUEST_URI']);
            $addfollowButton = "";

            if (isset($_POST['follow'])) {
                if ($getid != $id) {

                    if (!DB::query('SELECT friend_id FROM follow WHERE user_id=:userid AND friend_id=:friendid', array(':userid' => $getid, ':friendid' => $id))) {
                        DB::query('INSERT INTO follow VALUES(\'\', :userid, :friendid, NOW())', array(':userid' => $getid, ':friendid' => $id));
                    } else {
                        echo "hello";
                    }
                    $isFollowing = True;
                }
            }
            if (isset($_POST['unfollow'])) {

                if ($getid != $id) {
                    if (DB::query('SELECT friend_id FROM follow WHERE user_id=:userid AND friend_id=:friendid', array(':userid' => $getid, ':friendid' => $id))) {
                        DB::query('DELETE FROM follow WHERE user_id=:userid AND friend_id=:friendid', array(':userid' => $getid, ':friendid' => $id));
                    } else {
                    }
                    $isFollowing = False;
                }
            }

            if (DB::query('SELECT friend_id FROM follow WHERE user_id=:userid AND friend_id=:friendid', array(':userid' => $getid, ':friendid' => $id))) {
                $isFollowing = True;
            }

            if ($isFollowing == True) {
                $name = "unfollow";
                $value = "Unfollow";
            } elseif ($isFollowing == False) {
                $name = "follow";
                $value = "Follow";
            }

            if ($getid != $id) {
                $addfollowButton = "<form action='profile.php?id=$getid' method='post'>
            <input type='submit' name='$name' value='$value'> </form> ";
            }

            if (!User::location($id) == NULL) {
                $location = User::location($id);
                $displayL = True;
            } else {
                $location = "";
                $displayL = False;
            }

            if (!User::bio($id) == NULL) {
                $bio = User::bio($id);
                $displayB = True;
            } else {
                $bio = "";
                $displayB = False;
            }

            if (!User::work($id) == NULL) {
                $work = User::work($id);
                $displayW = True;
            } else {
                $work = "";
                $displayW = False;
            }

            if (!User::workplace($id) == NULL) {
                $company = User::workplace($id);
                $displayC = True;
            } else {
                $company = "";
                $displayC = False;
            }

            if (!User::university($id) == NULL) {
                $education = User::university($id);
                $displayE = True;
            } else {
                $education = "";
                $displayE = False;
            }

            $fileDestination = "";

            if (isset($_POST['post'])) {

                $postbody = $_POST['postbody'];

                if (isset($_POST['postImage'])) {

                    $fileName = $_FILES['postImage']['name'];
                    $fileTmp = $_FILES['postImage']['tmp_name'];
                    $fileSize = $_FILES['postImage']['size'];
                    $fileError = $_FILES['postImage']['error'];
                    $fileType = $_FILES['postImage']['type'];

                    print_r($_POST['postImage']);

                    $fileNameNew = "";

                    $fileExt = explode('.', $fileName);
                    $fileActualExt = strtolower(end($fileExt));

                    $allowed = array('jpg', 'jpeg', 'png');

                    if (in_array($fileActualExt, $allowed)) {
                        if ($fileError === 0) {
                            if ($fileSize < 1000000) {
                                $fileNameNew = uniqid('', true) .".".$fileActualExt;
                                $fileDestination = 'assets/img/posts/' . $fileNameNew;
                                move_uploaded_file($fileTmp, $fileDestination);
                                
                                echo $fileDestination;
                                //Post::submitPostImage($postbody, $fileDestination);
                            } else {
                                echo "3";
                            }
                        } else {
                            echo "2";
                        }
                    } else {
                        echo "1";
                    }
                }

                Post::submitPost($postbody);
            }

            if (isset($_GET['postid'])) {
                $postid = $_GET['postid'];
                Post::likePost($postid, $id);
            }
            $countPost = DB::rows('SELECT COUNT(*) FROM posts WHERE user_id=:userid', array(':userid' => $getid));
            $follows = DB::rows('SELECT COUNT(*) FROM follow WHERE friend_id=:userid', array(':userid' => $getid));

            if ($page = "profile.php?id=$getid") {

                if ($isFollowing == True || Login::isLoggedIn() == $getid) {
                    $createpost = '
                <div class="createContainer" onclick="openCreate()">
                    <div class="pcontentContainer">
                        <figure><img src="' . $profilePic . '"></figure>
                        <h1>What&#39;s on your mind, ' . $name . '</h1>
                    </div>
                </div>';
                } else {
                    $createpost = "";
                }
                $content = " $createpost 
                <div class='postsContainer'>
                    <div class='postWrapper'></div>
                    <div id='loading'>
                        <i class='fas fa-spinner fa-pulse'></i>
                    </div>
                </div>";
                $active = "post";

                if (isset($_GET['tab'])) {
                    $tab = $_GET['tab'];

                    if ($page = "profile.php?id=$getid" && $tab == 'following') {
                        $content = " <div class='followContainer'> " . Follow::followList($getid) . " </div>";
                        $active = "following";
                    } elseif ($page = "profile.php?id=$getid" && $tab == 'follower') {
                        $content = " <div class='followContainer'> " . Follow::followerList($getid) . " </div>";
                        $active = "follower";
                    } else {
                        die(header('Location: error.php'));
                    }
                }
            }
        }
    } else {
        die(header("Location: register.php"));
        die(header('Location: error.php'));
    }
} else {
    die(header("Location: register.php"));
}
