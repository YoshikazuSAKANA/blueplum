<?php
/**
 * ルーティング実行
 * Apche Rewriteにより、エンドポイントがすべてこのファイルとなる
 *
 * @author Yoshikazu Sakamoto
 * @category index
 * @package index.php
 */
require_once(__DIR__ . '/../config/Config.php');
require_once(_ROOT_DIR . '/app/Dispatcher.php');

// ログイン状態か判定
if (isset($_COOKIE[_MEMBER_SESSNAME])) {
    session_name(_MEMBER_SESSNAME);
    session_start();
}


//session_start();
$minutes = 2;

try {
    if ($minutes == 2) {
        // アクセスログ記述
        $excFunction = 'DBModel::dbmodel_callback_func';
    } else {
        $excFunction = 'BaseModel::basemodel_callback_func';
    }
    $excFunction = null;

    // IPアドレス
    $ip = getenv("REMOTE_ADDR");

    // アクセスログ記述
    if ($ip != '36.2.79.66') {

        $logger = new FileLogger('/home/y/share/pear/blueplum/log/test.txt');
        $logg = new DatabaseLogger();
        $BaseModel = new BaseModel($logger);
        $BaseModel->writeAccessLog($ip, $excFunction);
    }

    // ユーザーのルートを確保
    (new Dispatcher)->dispatch($conf);

} catch (Exception $e) {
    echo $e->getMessage();
}
