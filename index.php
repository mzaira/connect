<?php
$pagename = "Connect | Home";
require('private/includes/header.inc.php');
require('private/includes/logout.inc.php');
?>

<body>
    <div class="mainContainer">
        <!--Include the common elements in the main page-->
        <?php require('private/includes/sideBar.inc.php');
        require('private/includes/homeNavigation.inc.php');
        require('private/includes/createpost.inc.php'); ?>

        <div class="container">
            <div class="createContainer">
                <div class="contentContainer" onclick="openCreate()">
                    <figure><img src="<?php echo $profile_pic; ?>"></figure>
                    <h1>What's on your mind, <?php echo "$profileName"; ?>?</h1>
                </div>
            </div>
            <div class="postsContainer">
                <h1>Feeds</h1>

                <!--to call the posts (see script)-->
                <div class="postWrapper"></div>

                <!--to appear and disappear-->
                <div id="loading">
                    <i class="fas fa-spinner fa-pulse"></i>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            //show the loading icon
            $('#loading').show();

            //Original ajax request for loading first posts 
            $.ajax({
                url: "private/handler/index_posts.php",
                type: "POST",
                data: "page=1",
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
                        url: "private/handler/index_posts.php",
                        type: "POST",
                        data: "page=" + page,
                        cache: false,

                        success: function(response) {
                            //Removes current .nextpage 
                            $('.postWrapper').find('.nextPage').remove();
                            //Removes current .nextpage 
                            $('.postWrapper').find('.noMorePosts').remove();

                            //hide the loading; if there are posts
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