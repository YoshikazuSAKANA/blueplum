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

  protected $pdo;

    /**
     * MySQLに接続.
     * pdoイオブジェクトをインスタンス化
     *
     * @access public
     */
    public function db_connect() {
     try {
        $this->pdo = new PDO(_DSN,_DB_USER,_DB_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      } catch (PDOException $Exception) {
          echo '接続エラー：' . $Exception->getMessage();
          header('Location: http://os3-385-25562.vs.sakura.ne.jp/error');
          exit();
      }
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

    public static function dispErrorPage($errorMessage) {

        require_once(_VIEW_DIR . '/error.html');
        exit();
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

        if ($ip == '36.2.79.66') {

            // ログ書き込み
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

}
