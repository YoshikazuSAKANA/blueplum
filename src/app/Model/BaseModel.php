<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseDbModel
 *
 * @author nagatayorinobu
 */
class BaseModel {

    protected $pdo;

    protected function db_connect() {
     try {
        $this->pdo = new PDO(_DSN,_DB_USER,_DB_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      } catch (PDOException $Exception) {
          die('接続エラー：' . $Exception->getMessage());
      }
    }
}
