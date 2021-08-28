<?php
class Follow
{
    public static function followList($getid)
    {
        $dbfollow = DB::query(
            'SELECT follow.user_id FROM follow, users WHERE friend_id = users.id AND users.id = :userid',
            array(':userid' => $getid)
        );

        $followList = "";

        foreach ($dbfollow as $f) {
            $id = $f['user_id'];

            $followList .= '<div class="followGroup">
            <figure>
                <img src="' . User::profileImage($id) . '">
            </figure>
            <div class="heading">
                <a href="profile.php?id=' . $id . '">' . User::name($id) . '</a>
                <p>25 Mutual Followers</p>
            </div>
        </div>';
        }

        return $followList;
    }
    public static function followerList($getid)
    {
        $dbfollow = DB::query(
            'SELECT follow.friend_id FROM follow, users WHERE users.id = follow.user_id AND users.id = :userid',
            array(':userid' => $getid)
        );

        $followList = "";

        foreach ($dbfollow as $f) {
            $id = $f['friend_id'];

            $followList .= '<div class="followGroup">
            <figure>
                <img src="' . User::profileImage($id) . '">
            </figure>
            <div class="heading">
                <a href="profile.php?id=' . $id . '">' . User::name($id) . '</a>
                <p>25 Mutual Followers</p>
            </div>
        </div>';
        }

        return $followList;
    }
}
