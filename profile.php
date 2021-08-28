<?php
ob_start();

require 'private/includes/header.inc.php';
require 'private/includes/profile.inc.php';

?>

<body>
    <div class="mainContainer">
        <?php 
        require 'private/includes/navigation.inc.php';
        require 'private/includes/logout.inc.php';
        require 'private/includes/createpost.inc.php'; ?>

        <!--Navigation Div-->
        <nav id="navigation">
            <div class="logo">
                <img class="logo" src="assets/img/others/logo.png">
                <h1>Connect</h1>
            </div>
            <div class="nav">
                <h1>Menu</h1>
                <!--To redirect them to their corresponding pages-->
                <!--To add "active class" if the page is on their corresponding pages-->
                <a href="<?php echo "profile.php?id=$id" ?>" class="active">
                    <figure><img src="<?php echo User::profileImage(Login::isLoggedIn()); ?>"></figure> <?php echo User::name(Login::isLoggedIn()); ?>
                </a>
                <a href="../connect"><i class="fas fa-home fa-fw"></i><span>Home</span></a>
                <a href="settings.php"><i class="fas fa-cog fa-fw"></i><span>Settings</span></a>
                <!--To open the logout modal-->
                <form action="index.php" method="post">
                    <button type="submit" name="logout"><i class="fas fa-power-off fa-fw"></i><span>Logout</span></button>
                </form>
            </div>
        </nav>

        <div class="container" style="padding-right: 0px; display:flex; flex-direction:column; ">
            <div class="photos">
                <figure class="profilepic">
                    <img src="<?php echo $profilePic; ?>">
                </figure>
                <figure class="coverpic">
                    <img src="assets/img/coverPics/defaultCover.png">
                </figure>
                <a href="settings.php" class="cover"><i class="fas fa-camera fa-fw"></i><span>Edit Cover Photo</span></a>
                <a href="settings.php" class="profile"><i class="fas fa-camera fa-fw"></i></a>
            </div>

            <div class="profileContainer">
                <div class="contentContainer">
                    <div class="info">
                        <div class="heading">
                            <h1><?php echo "$Name"; ?></h1>
                            <?php if (!$username == "") {
                                echo "<p>$username</p>";
                            } ?>
                        </div>
                        <?php if ($displayB == False) {
                            echo "<a class='bio' href='settings.php'>Add Bio</a>";
                        } else {
                            echo "<p class='bio'>$bio</p>";
                        } ?>
                        <div class="section">
                            <?php echo $addfollowButton; ?>
                            <form action="profile.php?id=<?php echo $getid; ?>" method="get">
                                <input type="text" name="id" value="<?php echo $getid; ?>" style="display:none">
                                <a href="profile.php?id=<?php echo $getid; ?>" class="<?php if ($active == 'post') {
                                                                                            echo 'active';
                                                                                        } else {
                                                                                            echo '';
                                                                                        } ?>"><?php echo $countPost;
                                                                                                if ($countPost == 1) {
                                                                                                    echo " Post";
                                                                                                } else {
                                                                                                    echo " Posts";
                                                                                                } ?></a>
                                <button type="submit" name="tab" value="following" class="<?php if ($active == 'following') {
                                                                                                echo 'active';
                                                                                            } else {
                                                                                                echo '';
                                                                                            } ?>"> Following</button>
                                <button type="submit" name="tab" value="follower" class="<?php if ($active == 'follower') {
                                                                                                echo 'active';
                                                                                            } else {
                                                                                                echo '';
                                                                                            } ?>"> Followers</button>
                            </form>
                        </div>
                    </div>
                    <?php echo $content;  ?>
                </div>
                <div class="introContainer">
                    <div class="content">
                        <h1>Introduction</h1>
                        <div class="wrapper">
                            <div class="icontentContainer" style="<?php if ($displayL == False) {
                                                                        echo 'display:none;';
                                                                    } ?>">
                                <i class="fas fa-location-arrow"></i>
                                <span>Lives in <a><?php echo $location; ?></a></span>
                            </div>
                            <div class="icontentContainer" style="<?php if ($displayW == False && $displayC == False) {
                                                                        echo 'display:none;';
                                                                    } ?>">
                                <i class="fas fa-briefcase"></i>
                                <span><?php echo $work; ?> at <a><?php echo $company; ?></a></span>
                            </div>
                            <div class="icontentContainer" style="<?php if ($displayE == False) {
                                                                        echo 'display:none;';
                                                                    } ?>">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Graduated at <a><?php echo $education; ?></a></span>
                            </div>
                        </div>
                        <a class="iEdit" href="settings.php">Edit Intro</a>
                    </div>
                    <div class="privacy">
                        <div class="wrapper">
                            <p>​©2021 Connect, Inc All Rights Reserved</p>
                            <a href="">About Connect</a>
                            <a href="">Terms</a>
                            <a href="">Privacy & Cookie Policy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var profileId = '<?php echo $getid; ?>';

        $(document).ready(function() {

            $('#loading').show();

            //Original ajax request for loading first posts 
            $.ajax({
                url: "private/handler/profile_posts.php",
                type: "POST",
                data: "page=1&profileId=" + profileId,
                cache: false,

                success: function(data) {
                    $('#loading').hide();
                    $('.postWrapper').html(data);
                }
            });

            $(window).scroll(function() {
                //Div containing posts
                var height = $('.postWrapper').height();
                var scroll_top = $(this).scrollTop();
                var page = $('.postWrapper').find('.nextPage').val();
                var noMorePosts = $('.postWrapper').find('.noMorePosts').val();

                if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
                    $('#loading').show();

                    var ajaxReq = $.ajax({
                        url: "private/handler/profile_posts.php",
                        type: "POST",
                        data: "page=" + page + "&profileId=" + profileId,
                        cache: false,

                        success: function(response) {
                            //Removes current .nextpage 
                            $('.postWrapper').find('.nextPage').remove();
                            //Removes current .nextpage 
                            $('.postWrapper').find('.noMorePosts').remove();

                            $('#loading').hide();
                            $('.postWrapper').append(response);
                        }
                    });

                } //End if 

                return false;

            }); //End (window).scroll(function())


        });
    </script>
</body>

</html>