<nav id="homeNavigation">
    <div class="buttons">
        <div class="searchContainer">
            <form action="search.php" method="GET" name="search_form">
                <div class="input-group">
                    <input value="" type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>'), this.setAttribute('value', this.value);" name="q" autocomplete="off">
                    <label><i class="fas fa-search"></i> Search</label>
                </div>
            </form>

            <div class="searchContent">
                <div class="searchResult"></div>
                <div class="searchResultFooter"></div>
            </div>
        </div>
        <button id="notification"><i class="fas fa-bell fa-fw"></i></button>
    </div>

    <div class="requestContainer">
        <!--<div class="sugHeader">
            <h1>Suggestion For you</h1>
            <a href="friends.php">See All</a>
        </div>
        <div class="suggestion">
            <div class="newFriend">
                <figure>
                    <img src="assets/img/others/sample.png">
                </figure>
                <div class="heading">
                    <h1>Full Name is Abi</h1>
                    <p>2 Mutual Friends</p>
                </div>
                <form>
                    <button name="addFriend" type="submit">Add Friend</button>
                </form>
            </div>
            <div class="newFriend">
                <figure>
                    <img src="assets/img/others/sample.png">
                </figure>
                <div class="heading">
                    <h1>Full Name is Abi</h1>
                    <p>2 Mutual Friends</p>
                </div>
                <form>
                    <button name="addFriend" type="submit">Add Friend</button>
                </form>
            </div>
            <div class="newFriend">
                <figure>
                    <img src="assets/img/others/sample.png">
                </figure>
                <div class="heading">
                    <h1>Full Name is Abi</h1>
                    <p>2 Mutual Friends</p>
                </div>
                <form>
                    <button name="addFriend" type="submit">Add Friend</button>
                </form>
            </div>
        </div>-->

        <?php
        $rowData = DB::rows('SELECT COUNT(*) FROM posts WHERE user_id=:userid', array(':userid' => Login::isLoggedIn()));

        ?>

        <div class="latest">
            <h1>Latest Post Activity</h1>
            <?php
            if ($rowData > 0) {
                $post = DB::assoc('SELECT * FROM posts WHERE user_id=:userid ORDER BY id DESC', array(':userid' => Login::isLoggedIn()));
                $body = $post['body'];
                $likes = $post['likes'];
                $comments = DB::rows('SELECT COUNT(*) FROM comments WHERE post_id=:postid', array(':postid' => $post['id']));
                echo '
                <div class="latestPost">
                    <div class="label">
                        <p class="body">' . $body . '</p>
                        <div class="icons">
                        <p class="icon comment"><i class="fas fa-comment-dots"></i><span>' . $comments . '</span></p>
                        <p class="icon like"><i class="fas fa-heart"></i><span>' . $likes . '</span></p>
                        </div>
                    </div>
                </div>';
            } else {
                echo '
                <div class="noPost">
                    <div class="label">
                        <button onclick="openCreate()">No Available Post</button>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
    <div class="footer">
        <p>​©2021 Connect, Inc All Rights Reserved
            <a href="">About Connect</a>,
            <a href="">Terms</a>,
            <a href="">Privacy & Cookie Policy</a>
        </p>
    </div>
</nav>