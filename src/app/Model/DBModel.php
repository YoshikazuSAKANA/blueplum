<?php

class DBModel extends BaseModel {

    private $pdo;

    public function __construct() {
        $this->db_connect();
    }

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

    public function searchProductDetail($id) {

        try {
          $sql = 'SELECT * FROM cars WHERE id = :id ';
          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':id', $id, PDO::PARAM_INT);
          $stmh->execute();
          $result = $stmh->fetch(PDO::FETCH_ASSOC);
        
          return $result; 
        } catch (PDOException $Exception) {
          echo "ERROR: " . $Exception->getMessage();
        }
    }

    // ログインORマイページ遷移
    public function getUserInfo($userData, $dataType = 'mail_address') {

        try {
          $sql = 'SELECT * FROM member WHERE ' . $dataType . ' = :' . $dataType;

          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':'. $dataType, $userData, PDO::PARAM_STR);
          $stmh->execute();
          $result = $stmh->fetch(PDO::FETCH_ASSOC);

          return $result;
        } catch (PDOException $e) {
          echo "ERROR: " . $e->getMessage();
        }
    }

    public function registUser($postData) {

        try {
          $this->pdo->beginTransaction();
          // 同一のメールアドレスが存在しないか確認
          $sql = 'SELECT mail_address FROM member WHERE mail_address = :mail_address';

          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':mail_address', $postData['mail_address'], PDO::PARAM_STR);
          $stmh->execute();
          $result = $stmh->fetch(PDO::FETCH_ASSOC);
        if ($result['mail_address'] == $postData['mail_address']) {
            throw new PDOException('同じメールアドレスが存在します');
        }
          // 入力情報をDBに登録
          $sql = 'INSERT INTO member (last_name, first_name, birthday, mail_address, password) VALUES (:last_name, :first_name, :birthday, :mail_address, :password)';

          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':last_name', $postData['last_name'], PDO::PARAM_STR);
          $stmh->bindValue(':first_name', $postData['first_name'], PDO::PARAM_STR);
          $stmh->bindValue(':birthday', $postData['birthday'], PDO::PARAM_STR);
          $stmh->bindValue(':mail_address', $postData['mail_address'], PDO::PARAM_STR);
          $stmh->bindValue(':password', $postData['password'], PDO::PARAM_STR);
          $stmh->execute();
          $this->pdo->commit();
          echo "データを" . $stmh->rowCount() . "件挿入しました";
        } catch(PDOException $e) {
            $this->pdo->rollback();
            return false;
            echo "ERROR: " . $e->getMessage();
        }
    }

    public function checkExistMailAddress($mailAddress) {

        try {
          // 同一のメールアドレスが存在しないか確認
          $sql = 'SELECT mail_address FROM member WHERE mail_address = :mail_address';
          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':mail_address', $mailAddress, PDO::PARAM_STR);
          $stmh->execute();
          if ($stmh->rowCount() > 0) {
              return false;
          }
        } catch(PDOException $e) {
            echo "ERROR: " . $e->getMessage();
        }
    }

}
