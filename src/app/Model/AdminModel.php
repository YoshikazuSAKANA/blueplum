<?php

class AdminModel extends BaseModel {

    /**
     * コンストラクタ.
     * DBに接続します
     *
     * @access public
     */
    public function __construct() {
        parent::db_connect();
    }

    /**
     * 管理者情報を抽出.
     * ログインORマイページ遷移時に使用するメソッド
     *
     * @access public
     * @param var $userId
     * @return array $result
     */
    public function getAdminInfo($userId) {

        try {
          $sql = 'SELECT * FROM admin WHERE user_id = :user_id ';
          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':user_id', $userId, PDO::PARAM_STR);
          $stmh->execute();
          $result = $stmh->fetch(PDO::FETCH_ASSOC);
          return $result;
        } catch (PDOException $e) {
          echo "ERROR: " . $e->getMessage();
          return false;
        }
    }

    /**
     * ユーザー情報を取得
     *
     * @access public
     * @return array $result
     */
    public function getUserList() {

        try {
          $sql = 'SELECT user_id, last_name,  first_name, mail_address, birthday FROM member';
          $stmh = $this->pdo->prepare($sql);
          $stmh->execute();
          $result = $stmh->fetchAll(PDO::FETCH_ASSOC);
          return $result;
        } catch (PDOException $e) {
          echo "ERROR: " . $e->getMessage();
          return false;
        }
    }

    /**
     * ユーザー情報を取得
     *
     * @access public
     * @return array $result
     */
    public function getUserDetail($userId) {

        try {
          $sql = 'SELECT user_id, last_name,  first_name, mail_address, birthday FROM member WHERE user_id = :user_id';
          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':user_id', $userId, PDO::PARAM_INT);
          $stmh->execute();
          $result = $stmh->fetch(PDO::FETCH_ASSOC);
          return $result;
        } catch (PDOException $e) {
          echo "ERROR: " . $e->getMessage();
          return false;
        }
    }

    /**
     * 管理者情報を取得
     *
     * @access public
     * @return array $result
     */
    public function getAdminList() {

        try {
          $sql = 'SELECT user_id, admin_flg FROM admin';
          $stmh = $this->pdo->prepare($sql);
          $stmh->execute();
          $result = $stmh->fetchAll(PDO::FETCH_ASSOC);
          return $result;
        } catch (PDOException $e) {
          echo "ERROR: " . $e->getMessage();
          return false;
        }
    }

    /**
     * ユーザー情報を更新
     *
     * @access public
     * @return array $result
     */
    public function updateUserData($userData) {

        try {
          $this->pdo->beginTransaction();
          $sql = 'UPDATE member SET
                  first_name = :first_name,
                  last_name = :last_name,
                  mail_address = :mail_address,
                  birthday = :birthday WHERE user_id = :user_id';
          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':first_name', $userData['first_name'], PDO::PARAM_STR); 
          $stmh->bindValue(':last_name', $userData['last_name'], PDO::PARAM_STR); 
          $stmh->bindValue(':mail_address', $userData['mail_address'], PDO::PARAM_STR); 
          $stmh->bindValue(':birthday', $userData['birthday'], PDO::PARAM_STR); 
          $stmh->bindValue(':user_id', $userData['user_id'], PDO::PARAM_INT); 
          $stmh->execute();
          $this->pdo->commit();
          return true;
        } catch (PDOException $e) {
          echo "ERROR: " . $e->getMessage();
          return false;
        }
    }

    /**
     * ユーザー情報を削除
     *
     * @access public
     * @return array $result
     */
    public function deleteUserData($userId) {

        try {
          $this->pdo->beginTransaction();
          $sql = 'DELETE FROM member WHERE user_id = :user_id';
          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':user_id', $userId, PDO::PARAM_INT);
          $stmh->execute();
          $this->pdo->commit();
          return true;
        } catch (PDOException $e) {
          echo "ERROR: " . $e->getMessage();
          return false;
        }
    }

}

