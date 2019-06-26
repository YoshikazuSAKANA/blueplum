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

    public function getPdo() {
        return $pdo;
    }

    public function uploadFile() {

        //アップロード画像の詳細を格納
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

    public function dispErrorPage($errorMessage) {

        require_once(_VIEW_DIR . '/error.html');
        exit();
    }

}
