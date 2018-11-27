<?php
session_start();
require('dbconnect.php');

$post_email = '';
$post_password = '';

if (isset($_COOKIE['email']) && $_COOKIE['email'] !== '') {
	$post_email = $_COOKIE['email'];
}

// 自分自身にPOST=ログイン動作
// var_dump(empty($_POST));
if (!empty($_POST)) {
	$post_email = $_POST['email'];
	$post_password = $_POST['password'];
    if ($post_id !== '' && $post_password !== '') {
		$login = $db->prepare('SELECT * FROM members WHERE email = ? AND password = ?');
		// $login->execute(array($post_id, sha1($post_password)));
		$login->bindParam(1, $post_email);
		$enc_password = sha1($post_password);
		$login->bindParam(2, $enc_password);
		$login->execute();
		$record = $login->fetch();

		// 1件以上
		if($record) {
			// 投稿はidを使って行うので
			$_SESSION['id'] = $record['id'];
			$_SESSION['time'] = time();

			// ログイン情報の保存
			if ($_POST['save'] === 'on') {
				setcookie('email', $post_email, time() + 60*60*24*1);
			}

			header('Location: index.php');
			exit();
		} else {
			$error['login'] = 'failed';
		}
	} else {
		$error['login'] ='blank';
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>ログインする</title>
</head>

<body>
<div id="wrap">
<div id="head">
	<h1>ログインする</h1>
</div>
<div id="content">
	<div id="lead">
		<p>メールアドレスとパスワードを記入してログインしてください。</p>
		<p>入会手続きがまだの方はこちらからどうぞ。</p>
		<p>&raquo;<a href="join/">入会手続きをする</a></p>
	</div>
	<form action="" method="post">
	<dl>
		<?php if (isset($error['login'])): ?>
			<?php if ($error['login'] === 'blank'): ?>
				<p class="error">＊メールアドレスとパスワードを入力してください</p>
			<?php endif; ?>
			<?php if ($error['login'] === 'failed'): ?>
				<p class="error">＊メールアドレスまたはパスワードが正しくありません</p>
			<?php endif; ?>
		<?php endif; ?>
		<dt>メールアドレス</dt>
		<dd>
			<input type="text" name="email" size="35" maxlength="255" value="<?php print htmlspecialchars($post_email, ENT_QUOTES); ?>" />
		</dd>
		<dt>パスワード</dt>
		<dd>
			<input type="password" name="password" size="35" maxlength="255" value="<?php print htmlspecialchars($post_password, ENT_QUOTES); ?>" />
		</dd>
		<dt>ログイン情報の記録</dt>
		<dd>
			<input id="save" type="checkbox" name="save" value="on">
			<label for="save">次回からは自動的にログインする</label>
		</dd>
	</dl>
	<div>
		<input type="submit" value="ログインする" />
	</div>
	</form>
	</div>
<div id="foot">
	<p><img src="images/txt_copyright.png" width="136" height="15" alt="(C) H2O Space. MYCOM" /></p>
</div>
</div>
</body>
</html>
