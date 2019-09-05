<?php

function createLogoutMessage($userId) {

    // ログアウト日時
    $now = date("Y/m/d H:i:s");

    // リモートIPアドレス
    $ip = getenv("REMOTE_ADDR");

    $log = "{$now}  UserID:{$userId}  IP:{$ip}";
    return  $log;
}

/**
 * ZIPコードから住所を取得する（東京都のみ対応）
 *
 * @param $zipCode
 * @return $address
 */
function getUserAddress($zipCode) {

    $address = null;

    // ZIPコード保存ファイル
    $filename = '/home/y/share/pear/blueplum/tokyo_address.csv';

    $handle = fopen($filename, 'r');
    if ($handle) {
        while($line = fgetcsv($handle)) {
            if ($line[2] == $zipCode) {
                $address = $line[6] . $line[7] . $line[8];
                 break;
            }
        }
        fclose($handle);
    }
    return $address;
}

/**
 * JSONエンコードした値を出力して、終了
 *
 * @param $resultArray
 */
function returnJson($resultArray) {

    echo json_encode($resultArray);
    exit;
}

/**
 * 画像アップロード
 *
 * @return $file
 */
public function uploadFile() {

    // アップロード画像の詳細を格納
    $file = [];

    $fileTmpName = $_FILES['user_image']['tmp_name'];
    $filePath = _TMP_DIR .  $_FILES['user_image']['name'];

    if (move_uploaded_file($fileTmpName, $filePath)) {
        $file['user_image'] = $_FILES['user_image']['name'];
        $file['image_path'] = '/tmp/' . $_FILES['user_image']['name'];
        $file['size'] = getimagesize($filePath);
    }
    return $file;
}
