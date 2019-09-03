<?php

class UserDao extends DBModel {

    protected $logger;

    CONST PDO_ERROR = '予期せぬエラーが発生しました';

    public function __construct($logger) {

        parent::dbConnect();
        $this->logger = $logger;
    }

    public function getDaoFromMailAddress($mailAddress) {

        $sql = 'SELECT * FROM member WHERE mail_address = :mail_address';
        try {
          $stmt = $this->pdo->prepare($sql);
          $stmt->bindValue(':mail_address', $mailAddress, PDO::PARAM_STR);
          $stmt->execute();
          $arrUserData = $stmt->fetchAll();
          return $arrUserData;
        } catch (PDOException $e) {
          $this->processPdoError($e->getMessage());
        }
    }

    public function updateDaoLoginFailureInfo($userId, $failureCount, $nowTime) {

        $sql = 'UPDATE member SET login_failure_count = :login_failure_count, ';
        $sql.= 'login_failure_datetime  = :login_failure_datetime WHERE user_id = :user_id';
        try {
          $this->pdo->beginTransaction();
          $stmt = $this->pdo->prepare($sql);
          $stmt->bindvalue(':user_id',  $userId, PDO::PARAM_INT);
          $stmt->bindValue(':login_failure_count', $failureCount, PDO::PARAM_INT);
          $stmt->bindvalue(':login_failure_datetime',  $nowTime, PDO::PARAM_STR);
          $stmt->execute();
          $this->pdo->commit();
        } catch(PDOException $e) {
          $this->pdo->rollback();
          $this->processPdoError($e->getMessage());
        }
    }

    public function resetDaoLoginFailureInfo($userId) {

        $sql = 'UPDATE member SET login_failure_count = 0, ';
        $sql.= 'login_failure_datetime  = null WHERE user_id = :user_id';
        try {
          $this->pdo->beginTransaction();
          $stmt = $this->pdo->prepare($sql);
          $stmt->bindvalue(':user_id',  $userId, PDO::PARAM_INT);
          $stmt->execute();
          $this->pdo->commit();
        } catch(PDOException $e) {
          $this->pdo->rollback();
          $this->processPdoError($e->getMessage());
        }
    }

    /**
     * PDOエラーを処理.
     * エラー内容をログ出力し、エラーページを読み込む
     *
     * @access public
     * @param var $errorMessage
     * @return 
     */
    public function processPdoError($errorMessage):void {

        // 現在時刻
        $logDate = date('Y-m-d H:i:s');

        // ログメッセージ作成
        $logMessage = $logDate . ' ' . $errorMessage;

        // ログ出力 
        $this->logger->log($logMessage);

        // エラーページ読み込み
        parent::dispErrorPage(self::PDO_ERROR);
    }

}
