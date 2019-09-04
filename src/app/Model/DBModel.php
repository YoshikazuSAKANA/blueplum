<?php
/**
 * DBに関するクラス
 *
 * @author Yoshikazu Sakamoto
 * @category DB
 * @package Model
 */
class DBModel {

    protected $pdo;

    /**
     * MySQLに接続.
     * pdoイオブジェクトをインスタンス化
     *
     * @access public
     */
    public function dbConnect() {

     try {
        $this->pdo = new PDO(_DSN,_DB_USER,_DB_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      } catch (PDOException $e) {
          $errorMessage = '接続エラー：' . $e->getMessage();
          $this->dispErrorPage($errorMessage);
      }
    }

    /**
     * 商品情報を抽出.
     *
     * @access public
     * @param int $car_id
     * @return array $result
     */
    public function searchProductDetail($id) {

        try {
          $sql = 'SELECT * FROM cars WHERE car_id = :car_id ';
          $stmt = $this->pdo->prepare($sql);
          $stmt->bindValue(':car_id', $carId, PDO::PARAM_INT);
          $stmt->execute();
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          return $result; 
        } catch (PDOException $Exception) {
            $this->dispErrorPage($e->getMessage());
        }
    }

    /**
     * ユーザー情報を抽出.
     * ログインORマイページ遷移時に使用するメソッド
     *
     * @access public
     * @param var $userData
     * @param var $dataType
     * ログインか、マイページ遷移を判定
     * @return array $result
     */
    public function getUserInfo($input, $dataType = 'mail_address') {

        // ユーザー個人情報
        $userData = [];
        $sql = 'SELECT * FROM member WHERE ' . $dataType . ' = :' . $dataType;
        try {
          $this->dbConnect();
          $stmt = $this->pdo->prepare($sql);
          $stmt->bindValue(':'. $dataType, $input, PDO::PARAM_STR);
          $stmt->execute();
          $userData = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($dataType != 'mail_address') {
              $userData['USER_TASK'] = $this->getUserTask($userData['user_id']);
              unset($userData['password']);
          }
          return $userData;
        } catch (PDOException $e) {
            $this->dispErrorPage($e->getMessage());
        }
    }

     public function getUserTask($userId) {

        $sql = 'SELECT task_id, task FROM task WHERE user_id = :user_id AND done_flg = 0';
        try {
          $this->dbConnect();
          $stmt = $this->pdo->prepare($sql);
          $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
          $stmt->execute();
          $userTask = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $userTask;
        } catch (PDOException $e) {
            $this->dispErrorPage($e->getMessage());
        }
    }

    /**
     * ユーザー情報をMySQLに挿入.
     * 会員登録フォームで入力されたデータを挿入します
     *
     * @access public
     * @param array $postData
     * throws PDOException
     */
    public function registUser($postData) {

        try {
          $this->dbConnect();
          $this->pdo->beginTransaction();
          // 同一のメールアドレスが存在しないか確認
          $sql = 'SELECT mail_address FROM member WHERE mail_address = :mail_address';

          $stmt = $this->pdo->prepare($sql);
          $stmt->bindValue(':mail_address', $postData['mail_address'], PDO::PARAM_STR);
          $stmt->execute();
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result['mail_address'] == $postData['mail_address']) {
            throw new PDOException('同じメールアドレスが存在します');
        }
          // 入力情報をDBに登録
          $sql = 'INSERT INTO member (last_name, first_name, birthday, user_image, mail_address, password) VALUES (:last_name, :first_name, :birthday, :user_image, :mail_address, :password)';

          $stmt = $this->pdo->prepare($sql);
          $stmt->bindValue(':last_name'   , $postData['last_name']   ,PDO::PARAM_STR);
          $stmt->bindValue(':first_name'  , $postData['first_name']  ,PDO::PARAM_STR);
          $stmt->bindValue(':birthday'    , $postData['birthday']    ,PDO::PARAM_STR);
          $stmt->bindValue(':user_image'  , $postData['user_image']  ,PDO::PARAM_STR);
          $stmt->bindValue(':mail_address', $postData['mail_address'],PDO::PARAM_STR);
          $stmt->bindValue(':password'    , $postData['password']    ,PDO::PARAM_STR);
          $stmt->execute();
          $this->pdo->commit();
        } catch(PDOException $e) {
            $this->pdo->rollback();
            $this->dispErrorPage($e->getMessage());
        }
    }

    /**
     * メールアドレスを抽出.
     * 会員登録フォームでユーザーの入力したメールアドレスが存在するかどうか
     *
     * @access public
     * @param var $mailAddress
     * @return boolean メールアドレスが存在するかどうか
     * @throws PDOException
     */
    public function checkExistMailAddress($mailAddress) {

          // 同一のメールアドレスが存在しないか確認
          $sql = 'SELECT mail_address FROM member WHERE mail_address = :mail_address';
        try {
          $this->dbConnect();
          $stmt = $this->pdo->prepare($sql);
          $stmt->bindValue(':mail_address', $mailAddress, PDO::PARAM_STR);
          $stmt->execute();
          if ($stmt->rowCount() > 0) {
              return false;
          }
        } catch(PDOException $e) {
            $this->dispErrorPage($e->getMessage());
        }
    }

    /**
     * タスク追加
     *
     * @access public
     * @param var $entryTask
     * @return boolen
     * @throws PDOException
     */
    public function entryTask($userId, $entryTask) {

        $sql = 'INSERT INTO task (user_id, task) VALUES (:user_id, :task)';
        try {
          $this->dbConnect();
          $this->pdo->beginTransaction();
          $stmt = $this->pdo->prepare($sql);
          $stmt->bindValue(':user_id', $userId, PDO::PARAM_STR);
          $stmt->bindValue(':task', $entryTask, PDO::PARAM_STR);
          $stmt->execute();
          $this->pdo->commit();
          return true;
        } catch(PDOException $e) {
            $this->pdo->rollback();
            $this->dispErrorPage($e->getMessage());
        }
    }

    /**
     * タスク完了
     *
     * @access public
     * @param var $doneTask
     * @return boolen
     * @throws PDOException
     */
    public function doneTask($doneUserTask) {

        try {
          $this->dbConnect();
          $this->pdo->beginTransaction();
          foreach($doneUserTask as $value) {
              $sql = "UPDATE task SET done_flg = 1 WHERE task_id = :task_id";
              $stmt = $this->pdo->prepare($sql);
              $stmt->bindValue(':task_id', $value, PDO::PARAM_INT);
              $stmt->execute();
          }
          $this->pdo->commit();
          return true;
        } catch(PDOException $e) {
            $this->pdo->rollback();
            $this->dispErrorPage($e->getMessage());
        }
    }

    public static function dbmodel_callback_func() {

        echo "DBModel is back!!";
    }

    public static function dispErrorPage($errorMessage) {

        require_once(_VIEW_DIR . '/error.html');
        exit();
    }
}
