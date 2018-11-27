<?php
try {
    $db = new PDO('mysql:dbname=mini_bbs;host=127.0.0.1;charset=UTF8', 'root', '');
} catch(PDOException $e) {
    echo "DBエラー:" . $e->getMessage();
}


// htmlspecialcharsのショートカット
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// 本文内のURLにリンクを設定します
function makeLink($value) {
	return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>' , $value);
}