<?php
/**
 * DBに関するクラス
 *
 * @author Yoshikazu Sakamoto
 * @category DB
 * @package Model
 */
class DBModel extends BaseModel {

    protected $pdo;

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
          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':car_id', $carId, PDO::PARAM_INT);
          $stmh->execute();
          $result = $stmh->fetch(PDO::FETCH_ASSOC);
        
          return $result; 
        } catch (PDOException $Exception) {
          echo "ERROR: " . $Exception->getMessage();
          return false;
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
          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':'. $dataType, $input, PDO::PARAM_STR);
          $stmh->execute();
          $userData = $stmh->fetch(PDO::FETCH_ASSOC);
          if ($dataType != 'mail_address') {
              $userData['USER_TASK'] = $this->getUserTask($userData['user_id']);
              unset($userData['password']);
          }
          return $userData;
        } catch (PDOException $e) {
          echo "ERROR: " . $e->getMessage();
          return false;
        }
    }

     public function getUserTask($userId) {

        try {
          $sql = 'SELECT task_id, task FROM task WHERE user_id = :user_id AND done_flg = 0'; 

          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':user_id', $userId, PDO::PARAM_INT);
          $stmh->execute();
          $userTask = $stmh->fetchAll(PDO::FETCH_ASSOC);
          return $userTask;
        } catch (PDOException $e) {
          echo "ERROR: " . $e->getMessage();
          return false;
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
          $sql = 'INSERT INTO member (last_name, first_name, birthday, user_image, mail_address, password) VALUES (:last_name, :first_name, :birthday, :user_image, :mail_address, :password)';

          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':last_name', $postData['last_name'], PDO::PARAM_STR);
          $stmh->bindValue(':first_name', $postData['first_name'], PDO::PARAM_STR);
          $stmh->bindValue(':birthday', $postData['birthday'], PDO::PARAM_STR);
          $stmh->bindValue(':user_image', $postData['user_image'], PDO::PARAM_STR);
          $stmh->bindValue(':mail_address', $postData['mail_address'], PDO::PARAM_STR);
          $stmh->bindValue(':password', $postData['password'], PDO::PARAM_STR);
          $stmh->execute();
          $this->pdo->commit();
          return true;
        } catch(PDOException $e) {
            $this->pdo->rollback();
            return false;
            echo "ERROR: " . $e->getMessage();
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

    /**
     * タスク追加
     *
     * @access public
     * @param var $entryTask
     * @return boolen
     * @throws PDOException
     */
    public function entryTask($userId, $entryTask) {

        try {
          $this->pdo->beginTransaction();
          // 同一のメールアドレスが存在しないか確認
          $sql = 'INSERT INTO task (user_id, task) VALUES (:user_id, :task)';
          $stmh = $this->pdo->prepare($sql);
          $stmh->bindValue(':user_id', $userId, PDO::PARAM_STR);
          $stmh->bindValue(':task', $entryTask, PDO::PARAM_STR);
          $stmh->execute();
          $this->pdo->commit();
          return true;
        } catch(PDOException $e) {
            parent::dispErrorPage($e->getMessage());
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
          $this->pdo->beginTransaction();
          foreach($doneUserTask as $value) {
              $sql = "UPDATE task SET done_flg = 1 WHERE task_id = :task_id";

              $stmh = $this->pdo->prepare($sql);
              $stmh->bindValue(':task_id', $value, PDO::PARAM_INT);
              $stmh->execute();
          }
          $this->pdo->commit();
          return true;
        } catch(PDOException $e) {
            echo "ERROR: " . $e->getMessage();
            return false;
        }
    }

}
