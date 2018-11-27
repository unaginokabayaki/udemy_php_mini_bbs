<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id'])) {
    $member_id = $_SESSION['id'];
    $post_id = $_REQUEST['id'];

    $stat = $db->prepare('SELECT * FROM posts WHERE id = ?');
    $stat->execute(array($post_id));
    $message = $stat->fetch();

    // 投稿者が自分の場合削除
    if ($message['member_id'] == $member_id) {
        $del = $db->prepare('DELETE FROM posts WHERE id = ?');
        $del->execute(array($post_id));
    }
}

header('Location: index.php');
exit();
