<?php
/*
 * クローン実行ファイル.
 * 1時間毎に指定ファイルに時間を書き出すだけの不毛ファイル
 * X時59分に実行
 */

date_default_timezone_set('Asia/Tokyo');

$week = ['日', '月',  '火', '水', '木', '金', '土'];
$weekNum = date('w');
$today = date("m月d");

// 取得するアクセスログの時間
$getAccessErrorLog = date('Y/m/d H', strtotime("-1 hour"));

// アクセスログ保存ファイル
$file = '/home/y/share/pear/blueplum/log/access.log';

// 送信ログ
$message = null;

$fp = fopen($file, 'r');
while ($line = fgets($fp)) {
    $accessErrorDateUntilHour = strstr($line, ':', true);
    if ($accessErrorDateUntilHour == $getAccessErrorLog) {
      $message .= "{$line}\n";
    }
}

$title = "{$getAccessErrorLog}時 アクセスログ";

if (!empty($message)) {
    mb_send_mail('koushi1105@gmail.com', $title, $message, 'From: huitawarosu@yahoo.co.jp');
}
