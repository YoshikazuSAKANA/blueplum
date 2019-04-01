<?php

/*************************************************
 * ルーティング設定ファイル (Apache rewrite)
 *
 */

require_once('/home/y/share/pear/ymols/src/config/Config.php');
require_once(_ROOT_DIR . '/app/Dispatcher.php');

$dispatcher = new Dispatcher();
$dispatcher->dispatch($conf);

