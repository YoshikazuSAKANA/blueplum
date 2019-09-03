<?php

function createLogoutMessage($userId) {

    // ログアウト日時
    $now = date("Y/m/d H:i:s");

    // リモートIPアドレス
    $ip = getenv("REMOTE_ADDR");

    $log = "{$now}  UserID:{$userId}  IP:{$ip}";
    return  $log;
}
