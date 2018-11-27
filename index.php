<?php
session_start();
require('dbconnect.php');
// var_dump(session_name());

$message = '';
$reply_post_id = '';
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	// var_dump($_SESSION['id']);
	// ログインしている
	$_SESSION['time'] = time(); //最後の行動を記録

	$members = $db->prepare('SELECT * FROM members WHERE id = ?');
	$members->execute(array($_SESSION['id']));
	$member = $members->fetch();
} else {
	// ログインしていない
	header('Location: login.php'); exit();
}

// 投稿を記録する
if (!empty($_POST)) {
	if ($_POST['message'] !== '') {
		$stat = $db->prepare('INSERT INTO posts SET member_id = ?, message = ?, reply_message_id = ?, created = NOW()');
		$stat ->bindParam(1, $member['id']);
		$stat ->bindParam(2, $_POST['message']);
		$stat ->bindParam(3, $_POST['reply_post_id']);
		$stat ->execute();

		// 再読み込みで再投稿されてしまうので、もう一度読み直してPOSTをクリア
		header('Location: index.php');
		exit;
	} else {
		$error['message'] = 'blank';
	}
}

// 投稿数合計
$counts = $db->query('SELECT Count(*) AS cnt FROM posts');
$count = $counts->fetch();
$num_of_posts = $count['cnt'];

// 1ページの行数
$page_size = 6; 

// 最終ページを取得する
$request_page = 1;
if(isset($_REQUEST['page'])) {
	$request_page = $_REQUEST['page'];
}
$last_page = ceil($num_of_posts / $page_size);

// 最終ページを超えないように
$request_page = min($request_page, $last_page);

// 投稿を取得する
$page_start = ($request_page - 1) * $page_size;

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p 
					WHERE m.id = p.member_id ORDER BY p.created DESC LIMIT ?, ?');
// executeは文字列としてわたってしまうので、数字で渡す
$posts->bindParam(1, $page_start, PDO::PARAM_INT);
$posts->bindParam(2, $page_size, PDO::PARAM_INT);
$posts->execute();
// $posts->execute(array($page_start, $page_size));
// $posts = $db->query('SELECT m.name, m.picture, p.* FROM members m, posts p 
// 					WHERE m.id = p.member_id ORDER BY p.created DESC');


// 返信の場合
if (isset($_REQUEST['res'])) {
	$reply_post_id = $_REQUEST['res'];
	$response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p 
					WHERE m.id = p.member_id AND p.id = ?');
	$response->execute(array($_REQUEST['res']));
	$table = $response->fetch();
	$message = '@' . $table['name'] . ' ' . $table['message'] . "\n";
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="style.css" />
</head>

<body>
<div id="wrap">
	<div id="head">
		<h1>ひとこと掲示板</h1>
	</div>
	<div id="content">
		<div style="text-align: right"><a href="logout.php">ログアウト</a></div>

		<!-- 投稿フォーム -->
		<form action="" method="post">
			<dl>
				<dt><?php echo h($member['name']); ?>さん、メッセージをどうぞ</dt>
				<dd>
				<textarea name="message" cols="50" rows="5"><?php echo h($message); ?></textarea>
				<input type="hidden" name="reply_post_id" value="<?php echo h($reply_post_id); ?>" />
				</dd>
			</dl>
			<div>
				<p>
				<input type="submit" value="投稿する" />
				</p>
			</div>
		</form>

		<!-- メッセージ一覧 -->
		<?php foreach ($posts as $post): ?>
		<div class="msg">
			<!-- 上段 -->
			<p>
				<img src="member_picture/<?php print(h($post['picture'])); ?>" width="48" height="48" alt="<?php print(h($post['name'])); ?>" />
				<?php print(h($post['message'])); ?>
				<span class="name">（<?php print(h($post['name'])); ?>）</span>
				[<a href="index.php?res=<?php print(h($post['id'])); ?>">Re</a>]
			</p>
			<!-- 下段 -->
			<p class="day">
				<a href="view.php?id=<?php print(h($post['id'])); ?>"><?php print(h($post['created'])); ?></a>
				<?php if ($post['reply_message_id'] > 0): ?>
					<a href="view.php?id=<?php print(h($post['reply_message_id'])); ?>">返信元のメッセージ</a>
				<?php endif; ?>
				<?php if ($_SESSION['id'] == $post['member_id']): ?>
					[<a href="delete.php?id=<?php print(h($post['id'])); ?>" class="delete">削除</a>]
				<?php endif; ?>
			</p>
		</div>
		<?php endforeach; ?>

		<!-- ページ移動 -->
		<div>
			<ul class="paging">
				<?php if ($request_page > 1): ?>
				<li><a href="index.php?page=<?php print($request_page - 1); ?>">前のページへ</a></li>
				<?php else: ?>
				<li>前のページへ</li>
				<?php endif; ?>

				<?php if ($request_page < $last_page): ?>
				<li><a href="index.php?page=<?php print($request_page + 1); ?>">次のページへ</a></li>
				<?php else: ?>
				<li>次のページへ</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</body>
</html>
