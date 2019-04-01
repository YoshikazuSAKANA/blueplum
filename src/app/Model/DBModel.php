<?php

class DBModel extends BaseModel {

    public function searchALL() {

        parent::db_connect();

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

    public function getUserInfo($mailAddress) {

        parent::db_connect();

        try {
          $sql = 'SELECT * FROM member WHERE mail_address = :mail_address ';

          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':mail_address', $mailAddress, PDO::PARAM_STR);    
          $stmh->execute();
          $result = $stmh->fetch(PDO::FETCH_ASSOC);

          return $result;
        } catch (PDOException $e) {
          echo "ERROR: " . $e->getMessage();
        }
    }
}
