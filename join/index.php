<?php
session_start();
require('../dbconnect.php');
//php.iniのerror_reporting ~E_NOTICEを付ければ未定義エラーは出ないが
//$error = ['name' => '', 'email' => '', 'password' => '']; 
$post_name = '';
$post_email = '';
$post_password = '';
$fileName = '';
// var_dump($post_name);

// if ($_POST['name'] === '') {
// 	$error['name'] = 'blank';
// }

var_dump($_REQUEST); print "<br>";

//check.phpから戻ったされた時だけ
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {
	$_POST = $_SESSION['join'];
}

//送信された時だけ
if(!empty($_POST)) {

	// var_dump($_POST); print "<br>";
	$post_name = $_POST['name'];
    if ($post_name === '') {
		$error['name'] = 'blank';
	}
	$post_email = $_POST['email'];
    if ($post_email === '') {
		$error['email'] = 'blank';
	}
	$post_password = $_POST['password'];
    if ($post_password === '') {
		$error['password'] = 'blank';
	} elseif (strlen($post_password) < 4) {
		$error['password'] = 'length';
	}

	//ファイルタイプチェック
	if (isset($_FILES['image'])) {
		$fileName = $_FILES['image']['name'];
		if (!empty($fileName)) {
			$ext = substr($fileName, -3);
			if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
				$error['image'] = 'type';
			}
		}
	}

	//アカウント重複チェック
	if(empty($error)) {
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email = ?');
		$member->execute(array($post_email));
		$record = $member->fetch();
		if ($record['cnt'] > 0) {
			$error['email'] = 'duplicate';
		}
	}

	//チェックOK
	if (!isset($_REQUEST['action']) && empty($error)) {
		$image = '';
		if (!empty($_FILES['image'])) {
			//ファイル名 201811260001myface.png
			//var_dump($_FILES['image']); print "<br>";
			$image = date('YMdHis') . $_FILES['image']['name'];
			//ファイルを移動
			move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
		}

		// var_dump($image); print "<br>";
		// print "action";
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		header('Location: check.php');
		exit();
	}
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>会員登録</h1>
</div>

<div id="content">
<p>次のフォームに必要事項をご記入ください。</p>
<!-- actionを空にして自分自身にpost -->
<!-- actionを空にするとrewiteが残るので明示的に自分を指定 -->
<form action="index.php" method="post" enctype="multipart/form-data">
	<dl>
		<dt>ニックネーム<span class="required">必須</span></dt>
		<dd>
			<input type="text" name="name" size="35" maxlength="255" value="<?php print htmlspecialchars($post_name, ENT_QUOTES); ?>" />
			<?php if (isset($error['name']) && $error['name'] === 'blank'): ?>
				<p class="error">＊ニックネームを入力してください</p>
			<?php endif; ?>
		</dd>
		<dt>メールアドレス<span class="required">必須</span></dt>
		<dd>
			<input type="text" name="email" size="35" maxlength="255" value="<?php print htmlspecialchars($post_email, ENT_QUOTES); ?>" />
			<?php if (isset($error['email']) && $error['email'] === 'blank'): ?>
				<p class="error">＊メールアドレスを入力してください</p>
			<?php endif; ?>
			<?php if (isset($error['email']) && $error['email'] === 'duplicate'): ?>
				<p class="error">＊指定されたメールアドレスは既に登録されています</p>
			<?php endif; ?>
		<dt>パスワード<span class="required">必須</span></dt>
		<dd>
			<input type="password" name="password" size="10" maxlength="20" value="<?php print htmlspecialchars($post_password, ENT_QUOTES); ?>" />
			<?php if (isset($error['password']) && $error['password'] === 'blank'): ?>
				<p class="error">＊パスワードを入力してください</p>
			<?php endif; ?>
			<?php if (isset($error['password']) && $error['password'] === 'length'): ?>
				<p class="error">＊パスワードは４文字以上で入力してください</p>
			<?php endif; ?>
        </dd>
		<dt>写真など</dt>
		<dd>
			<input type="file" name="image" size="35" />
			<?php if (isset($error['image']) && $error['image'] === 'type'): ?>
				<p class="error">＊画像はjpg, gif, pngを指定してください</p>
			<?php endif; ?>
			<?php if (!empty($error)): ?>
				<p class="error">＊再度画像を指定してください</p>
			<?php endif; ?>
        </dd>
	</dl>
	<div><input type="submit" value="入力内容を確認する" /></div>
</form>
</div>
</body>
</html>
