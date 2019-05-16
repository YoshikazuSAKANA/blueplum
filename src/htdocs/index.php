<?php
/**
 * ルーティング実行
 * Apche Rewriteにより、エンドポイントがすべてこのファイルとなる
 *
 * @author Yoshikazu Sakamoto
 * @category index
 * @package index.php
 */
require_once('/home/y/share/pear/blueplum/src/config/Config.php');
require_once(_ROOT_DIR . '/app/Dispatcher.php');

// クッキーが存在すれば、セッションスタート
if (isset($_COOKIE[_MEMBER_SESSNAME])) {
    session_name(_MEMBER_SESSNAME);
    session_start();
}

// ユーザーのルートを確保
$dispatcher = new Dispatcher();
$dispatcher->dispatch($conf);

