<?php

/*************************************************
 * ルーティング設定ファイル (Apache rewrite)
 *
 */

require_once('/home/y/share/pear/blueplum/src/config/Config.php');
require_once(_ROOT_DIR . '/app/Dispatcher.php');

if (isset($_COOKIE[_MEMBER_SESSNAME])) {
    session_name(_MEMBER_SESSNAME);
    session_start();
}

$dispatcher = new Dispatcher();
$dispatcher->dispatch($conf);

