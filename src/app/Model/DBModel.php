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
          die('接続エラー：' . $Exception->getMessage());
      }
    }

    public function searchALL() {

        $id = 1;
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

    public function registUser($input) {
print_r($input);
        try {
          $sql = 'INSERT INTO member (last_name, first_name, mail_address, password) VALUES (:last_name, :first_name, :mail_address, :password)';

          $this->pdo->beginTransaction();
          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':last_name', $input['last_name'], PDO::PARAM_STR);
          $stmh->bindValue(':first_name', $input['first_name'], PDO::PARAM_STR);
          $stmh->bindValue(':mail_address', $input['mail_address'], PDO::PARAM_STR);
          $stmh->bindValue(':password', $input['password'], PDO::PARAM_STR);
          $stmh->execute();
          $this->pdo->commit();
          echo "データを" . $stmh->rowCount() . "件挿入しました";
        } catch(PDOException $e) {
            $this->pdo->rollback();
            echo "ERROR: " . $e->getMessage();
        }
    }

}
