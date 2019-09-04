<?php
/*
 * クローン実行ファイル.
 * 1時間毎に指定ファイルに時間を書き出すだけの不毛ファイル
 * X時59分に実行
 */

date_default_timezone_set('Asia/Tokyo');

$week = [
  '日', //0
  '月', //1
  '火', //2
  '水', //3
  '木', //4
  '金', //5
  '土', //6
];
$weekNum = date('w');
$nowDate = date("Y/m/d H:i:s");

// 取得するアクセスログの時間
$getAccessErrorLog = date('Y/m/d H', strtotime("-1 hour"));
$file = '/home/y/share/pear/blueplum/log/access.log';

$fp = fopen($file, 'r')
while($line = fgets($fp)) {
    $accessErrorDateUntilHour = strstr($line, ':', true);
    if ($accessErrorDateUntilHour == $getAccessErrorLog) {
      $message .= "{$line}\n";
    }
}

mb_send_mail('koushi1105@gmail.com', "[{$nowDate}({$week[$weekNum]})]アクセスログ", $message, 'From: huitawarosu@yahoo.co.jp');
