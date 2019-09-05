<?php
/**
 * ユーザーモデル
 * @author Yoshikazu Sakamoto
 */
class UserModel {

    private $userId = null;
    private $password = null;
    private $lastNama = null;
    private $firstName = null;
    private $birthday = null;
    private $userImage = null;
    private $mailAddress = null;
    private $loginFailureCount = null;
    private $loginFailureDatetime = null;
    private $deleteFlg = false;

    protected $logger;
    protected $loggerPDO;

    // ログイン失敗制限回数
    CONST LOCK_COUNT = 3;

    // アカウントロック時間
    CONST LOCK_TIME = '-1 min';

    public function __construct($logger) {

        $this->logger = $logger;
        $this->loggerPDO = new FileLogger('/home/y/share/pear/blueplum/log/pdo.log');
    }

    /**
     * メールアドレスからユーザー情報を抽出.
     * ユーザー情報を設定する
     *
     * @access public
     * @param var $mailAddress
     * @return 
     */
    public function getUserModelByMailAddress($mailAddress) {

        // ユーザー情報を取得
        $UserDao = new UserDao($this->loggerPDO);
        $dao = $UserDao->getDaoFromMailAddress($mailAddress);

        // 取得に成功していれば、値をプロパティにセット
        if (isset($dao[0])) {
            $this->setProperty($dao[0]);
            return true;
        }
        return false;
    }

    /**
     * ユーザー情報をプロパティにセット
     *
     * @access public
     * @param array $arrDao
     * @return 
     */
    public function setProperty($arrDao):void {

        $this->setUserId($arrDao['user_id']);
        $this->setPassword($arrDao['password']);
        $this->setLastName($arrDao['last_name']);
        $this->setFirstName($arrDao['first_name']);
        $this->setBirthday($arrDao['birthday']);        
        $this->setUserImage($arrDao['user_image']);
        $this->setMailAddress($arrDao['mail_address']);
        $this->setLoginFailureCount($arrDao['login_failure_count']);
        $this->setLoginFailureDate($arrDao['login_failure_datetime']);
        $this->setDeleteFlg($arrDao['delete_flg']);
    }

    /**
     * アカウントロック確認
     *
     * @access public
     * @return boolean
     */
    public function isAccountLock() {

        $lastFailureTime = $this->getLoginFailureDatetime();

        // ログイン失敗情報が存在しない
        if (empty($lastFailureTime)) { 
            return false;
        }

        // TimeStamp型に変換
        $strLastFailureTime = date("Y-m-d H:i:s", strtotime($lastFailureTime));
        $srtAccountLockTime = date("Y-m-d H:i:s", strtotime(self::LOCK_TIME));

        // 最終ログイン失敗日時が30分以内かつログイン失敗回数が3回以上
        if ($srtAccountLockTime < $strLastFailureTime && self::LOCK_COUNT <= $this->loginFailureCount) {
            return true;
        }
        return false;
    }

    /**
    * ログイン失敗記録.
    * DBにログイン失敗回数と日時を挿入
    *
    * @access public
    * @return
    */
    public function loginFailureIncrement():void {

        $failureCount = $this->getLoginFailureCount();
        $UserDao = new UserDao($this->loggerPDO);
        $nowTime = date('Y/m/d H:i:s');
        $updateFailureCount = $failureCount+1;
        $UserDao->updateDaoLoginFailureInfo($this->getUserId(), $updateFailureCount, $nowTime);
        $content = ($updateFailureCount >= 3)? '[NOTICE] Account suspension' : "{$updateFailureCount}回目の失敗";
        $this->logger->log("{$nowTime}  user_id:{$this->getUserId()}  {$content}");
    }

    /**
    * ログイン失敗情報のリセット.
    * DBにログイン失敗回数と日時を更新
    *
    * @access public
    * @return
    */
    public function resetLoginInfo() :void{

        $UserDao = new UserDao($this->loggerPDO);
        $UserDao->resetDaoLoginFailureInfo($this->getUserId());
    }

    /**
    * アカウントロック通知メール
    * ユーザーにメールを送信
    *
    * @access public
    * @return
    */
    public function noticeAccountLock() {

        // 本文
        $message = "お使いのアカウント {$this->getMailAddress()}はロックされました\n";
        $message.= "30分経過すると、ログインが実行できます\n";
        $message.= "http://os3-385-25562.vs.sakura.ne.jp/signin";

        // メール送信
        mb_send_mail($this->getMailAddress(), 'アカウント停止', $message, _HEADERS);
    }

    public function getUserId() {

        return $this->userId;
    }

    public function getPassword() {

        return $this->password;
    }

    public function getFirstName() {

        return $this->firstName;
    }

    public function getMailAddress() {

        return $this->mailAddress;
    }

    public function getLoginFailureCount() {

        return $this->loginFailureCount;
    }

    public function getLoginFailureDatetime() {

        return $this->loginFailureDatetime;
    }

    public function setUserId($userId) {

        $this->userId = $userId;
    }

    public function setPassword($password) {

        $this->password = $password;
    }

    public function setLastName($lastName) {

        $this->lastName = $lastName;
    }

    public function setFirstName($firstName) {

        $this->firstName = $firstName;
    }

    public function setBirthday($birthday) {

        $this->birthday = $birthday;
    }

    public function setUserImage($userImage) {

        $this->userImage = $userImage;
    }

    public function setMailAddress($mailAddress) {

        $this->mailAddress = $mailAddress;
    }

    public function setLoginFailureCount($loginFailureCount) {

        $this->loginFailureCount = $loginFailureCount;
    }

    public function setLoginFailureDate($loginFailureDatetime) {

        $this->loginFailureDatetime = $loginFailureDatetime;
    }

    public function setDeleteFlg($deleteFlg) {

        $this->deleteFlg = $deleteFlg;
    }
}
