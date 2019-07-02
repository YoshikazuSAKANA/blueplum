<?php
/**
 * ユーザー認証に関するクラス
 *
 * @author Yoshikazu Sakamoto
 * @category Auth
 * @package Model
 */
class Auth {

    // 認証情報の格納先名
    private $authName;

    // セッション名
    private $sessName;

    // 会員登録通知メール先
    public static $noticeMailAddress = 'huitawarosu@yahoo.co.jp';

    // タイトル
    CONST SUBJECT = 'テストメール';

    // ヘッダー
    CONST HEADERS = 'From: test_sakamoto_test@gmail.com';

    /**
     * セッションスタートを実行.
     * セッション名を命名する
     *
     * @access public
     * @return
     */
    public function start(){
        // セッションが既に開始している場合は何もしない。
        if(session_status() ===  PHP_SESSION_ACTIVE){
            return;
        }
        // セッション開始
        session_name(_MEMBER_SESSNAME);
        session_start();
    }
    
     /**
      * セッション情報を確認
      *
      * @access public
      */
    public function check(){
        if(!empty($_SESSION[$this->getAuthName()]) && $_SESSION[$this->getAuthName()]['user_id'] >= 1){
            return true;
        }
    }

     /**
      * パスワードのハッシュ化
      *
      * @access public
      * @param var $password
      * @return int $hash
      */
    public function getHashedPassword($password) {

        // ハッシュパスワードの生成
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $hash;
    }

    /**
      * パスワードの認証.
      * ハッシュ化パスワードを戻し。認証
      *
      * @access public
      * @param var $password
      * @param hash $hashedPassword
      * @return boolean パスワードが正しいかどうか
      */
    public function checkPassword($password, $hashedPassword){
        if (password_verify($password, $hashedPassword)) {
            return true;
        }
    }
    
    // 認証情報の取得
    public function authOk($userdata){
        session_regenerate_id(true);
        $_SESSION[$this->getAuthName()] = $userdata;
    }

    public static function authNo(){
        return 'ユーザ名かパスワードが間違っています。'."\n";
    }
    

    /**
     * 認証情報を破棄.
     * セッション、クッキーを破棄する
     * @access public
     */
    public function logout(){

        // セッション変数を空にする
        $_SESSION = [];

        // クッキーを削除
        // クッキーをクライアント側で保存しているかどうか
        if (ini_get("session.use_cookies")) {
            // セッションクッキーのパラメータを取得
            $params = session_get_cookie_params();

            setcookie(session_name(), '', time() + 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // セッションを破壊
        session_destroy();
    }

    /**
     * メール送信 (callable)
     * @access public
     */
    public function sendMail($userData, $method){

        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        self::$method($userData);
    }

    /**
     * 会員登録後にメール送信
     * @access public
     */
    public function toUserRegistMail($userData){

        // 本文
        $message = $userData['first_name'] . 'さん、会員登録ありがとうございます';

        // メール送信
        mb_send_mail($userData['mail_address'], self::SUBJECT, $message, self::HEADERS);
    }

    /**
     * 会員登録後にメール送信
     * @access public
     */
    public function toNoticeRegistMail($userData){

        // 本文
        $message = $userData['first_name'] . 'さんが会員登録しました';

        // メール送信
        mb_send_mail(static::$noticeMailAddress, self::SUBJECT, $message, self::HEADERS);
    }

}

?>

