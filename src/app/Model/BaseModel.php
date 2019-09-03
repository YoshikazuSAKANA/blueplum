<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseDbModel
 *
 * @author Yoshikazu Sakamoto
 */
class BaseModel {

  protected $logger;

    public function __construct($logger) {

        $this->logger = $logger;
    }

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

    public function writeAccessLog($func = "") {

        // アクセス時刻
        $time = date("Y/m/d H:i");

        // IPアドレス
        $ip = getenv("REMOTE_ADDR");

        // ホスト名
        $host = getenv("REMOTE_HOST");

        // リファラ
        $referer = !empty(getenv("HTTP_REFERER")) ?  getenv("HTTP_REFERER") : "NO_Referer";

        // ログ本文
        $log = $time .",  ". $ip . ",  ". $host. ",  ". $referer . PHP_EOL;

        if ($ip != '36.2.79.66') {
            $message = 'OUT!';
            // ログ書き込み
            $this->logger->log($message);
            $fileName = "/home/y/share/pear/blueplum/log/access_log.txt";
            $fp = fopen($fileName, "a");
            fputs($fp, $log);
            fclose($fp);
        }
        if (!empty($func)) {
            call_user_func($func);
        }
    }

    public static function basemodel_callback_func() {

        echo "BaseModel is back!!";
    }

    public function getUserAddress($zipCode) {

        $address = null;
        // 現在時刻
        $now = date('Y-m-d H:i:s');

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
        if (empty($address)) {
            $this->logger->log("{$now} [FAILED]  Get the zipcode: {$zipCode}");
        } else {
            $this->logger->log("{$now} [SUCCESS] Get the zipcode: {$zipCode}  {$address}");
        }
        return $address;
    }
}
