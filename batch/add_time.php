<?php
/*
 * クローン実行ファイル.
 * 1時間毎に指定ファイルに時間を書き出すだけの不毛ファイル
 *
 */

date_default_timezone_set('Asia/Tokyo');

$file = '/home/y/share/pear/blueplum/src/app/View/admin_test_cron.html';

$current = file_get_contents($file);
$current .= date("Y-m-d H:i:s") . "<BR>";

//file_put_contents($file, $current);
