<?php
require 'lib/password.php';   // password_hash()はphp 5.5.0以降の関数のため、バージョンが古くて使えない場合に使用
// セッション開始
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "user-manager";  // ユーザー名
$db['pass'] = "brute-force16";  // ユーザー名のパスワード
$db['dbname'] = "user_management";  // データベース名

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$signUpMessage = "";

// ログインボタンが押された場合
if (isset($_POST["signUp"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST["username"])) {  // 値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    } else if (empty($_POST["password2"])) {
        $errorMessage = 'パスワードが未入力です。';
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"] === $_POST["password2"]) {
        // 入力したユーザIDとパスワードを格納
        $username = $_POST["username"];
        $password = $_POST["password"];

        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = 'mysql:dbname=user_management;host=localhost';

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass']);

            $stmt = $pdo->prepare("INSERT INTO user(name, password) VALUES (?, ?)");

            $stmt->execute(array($username, password_hash($password, PASSWORD_DEFAULT)));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
            $userid = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$useridに入れる

            header("Location: rakurakupg.html");  // メイン画面へ遷移
            exit();  // 処理終了
            
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            echo $e->getMessage();
        }
    } else if($_POST["password"] != $_POST["password2"]) {
        $errorMessage = 'パスワードに誤りがあります。';
    }
}
?>

<!doctype html>
<html>
<link rel="stylesheet" href="Login.css"  />
<div class="login">
    <head>
            <meta charset="UTF-8">
            <title>新規登録</title>
    </head>

    <body>
        <h1>新規登録画面</h1>
        <form id="loginForm" name="loginForm" action="" method="POST">
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <div><font color="#ffff00"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
                <label for="username"></label><input type="text" id="username" name="username" placeholder="ユーザー名を入力" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
                <br>
                <label for="password"></label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力">
                <br>
                <label for="password2"></label><input type="password" id="password2" name="password2" value="" placeholder="再度パスワードを入力">
                <br>
                <input type="submit" id="signUp" name="signUp" value="登録" class="btn btn-primary btn-block btn-large">
        </form>
        <br>
        <form action="Login.php">
            <input type="submit" value="ログイン画面へ" class="btn btn-primary btn-block btn-large">
        </form>
    </body>
</div>
</html>
