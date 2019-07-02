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

$minutes = 1;

try{
  if ($minutes % 2 == 0) {
      // アクセスログ記述
      $excFunction = 'DBModel::dbmodel_callback_func';
  } else {
      $excFunction = 'BaseModel::basemodel_callback_func';
  }

  // アクセスログ記述 
  (new BaseModel)->writeAccessLog($excFunction);
  // ユーザーのルートを確保
  (new Dispatcher)->dispatch($conf);

} catch (Exception $e) {
    $e->getMessage();
    echo "エラー発生";
}
