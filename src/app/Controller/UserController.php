<?php

/**
 * ユーザ情報に関するクラス
 *
 * ログイン機能や会員登録に関するメソッドをまとめたアクションクラス
 *
 * @access public
 * @author YoshikazuSakamoto
 * @category User
 * @package Controller
*/
class UserController {

    /**
     * ユーザーのログインを実行.
     * メールアドレス、パスワードをチェックします
     *
     * @access public
     */
    public function SigninAction() {

        $logger = new FileLogger('/home/y/share/pear/blueplum/log/user_signin.log');

        $inputMailAddress = htmlspecialchars($_POST['mail_address'], ENT_QUOTES, 'UTF-8');
        $inputPassword    = htmlspecialchars($_POST['password'],     ENT_QUOTES, 'UTF-8');

        $AuthModel = new Auth;
        $objUserModel = new UserModel($logger);
        try {
          if ($objUserModel->getUserModelByMailAddress($inputMailAddress) === false) {
              throw new Exception('ログインに失敗しました');
          }

          // アカウントロックを確認
          $accountLock = false;
          if ($objUserModel->isAccountLock()) {
              throw new Exception('アカウントはロックされています');
              $accountLock = true;
          }

          // パスワードを確認
          if ($AuthModel->checkPassword($inputPassword, $objUserModel->getPassword()) === false) {

              // ログイン失敗記録
              $objUserModel->loginFailureIncrement();

              // アカウント停止通知
              $objUserModel->noticeAccountLock();

              // ログインに失敗しました
              throw new Exception('ログインに失敗しました');
          }
          // ログイン失敗情報リセット
          $objUserModel->resetLoginInfo();

          // セッションスタート
          $AuthModel->start();
          $_SESSION['user_id']   = $objUserModel->getUserId();
          $_SESSION['user_name'] = $objUserModel->getFirstName();
          require_once(_VIEW_DIR . '/top.html');
        } catch(Exception $e) {
          $errorMessage = $e->getMessage();
          require_once(_VIEW_DIR . '/signin.html');
        }
    }

    /**
     * ユーザーの会員登録を実行
     * フォーム画面、確認画面それぞれで実行
     * 処理内容によって下記番号を表示
     * 0: 登録確認画面
     * 1: トップ画面
     * 2: 登録ファーム画面
     * 3: エラー画面
     *
     * @access public
     */
    public function SignupAction() {

        // 画面出しわけ
        $pageFlg = '3';

        // ユーザー入力値
        $postData = array();

        $uploadFile = [];

        // エラー文
        $error;

        if (!empty($_POST)) {
            foreach($_POST as $key => $value) {
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                // 制御文字以外で、０から100文字以内であるかどうか
                if (preg_match('/\A[[:^cntrl:]]{0,100}\z/u', $value) === 1) {
                    $postData[$key] = $value;
                } else {
                    die("予期せぬエラーが発生しました。");
                }
            }
        }
        $Validation = new Validation;

        // レスポンス値を整形する
        $postData = $Validation->formatPostData($postData);

        // レスポンス値(整形後)のバリデーション
        $error = $Validation->validate($postData);

        if (empty($error)) {
            if (isset($postData['btn_confirm'])) {
                $uploadFile = (new BaseModel)->uploadFile();
                $pageFlg = '0';
            } elseif (isset($postData['btn_signup'])) {
                if (!empty($postData['user_image'])) {
                    rename('tmp/' . $postData['user_image'], 'image/' . $postData['user_image']);
                }
                $Auth = new Auth;
                $DBModel = new DBModel;
                // パスワードのハッシュ化
                $postData['password'] = $Auth->getHashedPassword($postData['password']);
                if ($DBModel->registUser($postData)) {
                    // $Auth->sendMail($postData, 'toNoticeRegistMail');
                    // $Auth->sendMail($postData, 'toUserRegistMail');
                    $pageFlg = '1';
                }
            }
        } else {
            if (isset($postData['btn_confirm'])) {
                $pageFlg = '2';
            } elseif (isset($postData['btn_signup'])) {
                $pageFlg = '3';
            }
        }
        switch($pageFlg) {
            // 登録フォーム確認
            case '0':
                require_once(_VIEW_DIR . '/confirm_signup.html');
                break;
            // 登録完了
            case '1':
                require_once(_VIEW_DIR . '/done.html');
                break;
            // 登録フォーム
            case '2':
                require_once(_VIEW_DIR . '/signup.html');
                break;
            // エラー
            case '3':
                header('Location: http://os3-385-25562.vs.sakura.ne.jp/error');
                exit();
        }
    }

    /**
     * ユーザーのログアウトを実行
     *
     * @access public
     */
    public function LogoutAction() {

        $logger = new FileLogger('/home/y/share/pear/blueplum/log/user_logout.log');
        $token = (isset($_POST['token']))? htmlspecialchars($_POST['token'], ENT_QUOTES, 'UTF-8') : '';
        $userId = htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8');

        // CSRF対策の為、トークン発行済み
        if (!empty($userId) && $token == session_id()) {

            // ログアウト実行
            (new Auth)->logout();

            // ログ生成
            $log = createLogoutMessage($userId);

            // ログ出力
            $logger->log($log);
            require_once(_VIEW_DIR . '/top.html');
        } else {
            header('Location: http://os3-385-25562.vs.sakura.ne.jp/error');
            exit();
        }
    }

    /**
     * ユーザーのマイページを表示.
     * ユーザーIDを使用して、DBから値を抽出
     *
     * @access public
     * @param int $userId
     */
    public function MyPageAction($userId)
    {
        // ユーザー情報抽出
        if (
            !empty($userId)
            && isset($_SESSION['user_id'])
            && $_SESSION['user_id'] == $userId
        ) {
            $userData = (new DBModel)->getUserInfo($userId, 'user_id');
            require_once(_VIEW_DIR . '/mypage.html');
        } else {
            header('Location: http://os3-385-25562.vs.sakura.ne.jp/error');
            exit();
        }
    }

    /**
     * タスク追加(POSTver)
     *
     * @access public
     */
    public function entryTaskAction() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST'
           && !empty($_POST['user_id'])
           && !empty($_POST['entry_task'])
           && session_id() == $_POST['token']
        ) {
            $userId = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8');
            $entryTask = htmlspecialchars($_POST['entry_task'], ENT_QUOTES, 'UTF-8');
            $DBModel = new DBModel;
            $DBModel->entryTask($userId, $entryTask);
        }
        $this->MyPageAction($userId);
    }

    /**
     * タスク追加(JSver)
     *
     * @access public
     */
    public function ajaxAction() {

        $userId    = $_POST['user_id'];
        $entryTask = $_POST['entry_task'];
        $DBModel = new DBModel;
        if ($DBModel->entryTask($userId, $entryTask)) {
            return $entryTask;
        } else {
            return false;
        }
    }

    /**
     * タスク完了
     *
     * @access public
     */
    public function doneUserTaskAction() {

        // 完了したタスクIDを格納
        $doneTaskId = [];

        if (isset($_POST['user_id'])) {
            $userId = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8');
        }
        if (!empty($_POST['task_id'])) {
            foreach($_POST['task_id'] as  $value) {
                $doneTaskId[] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
            (new DBModel)->doneTask($doneTaskId);
        }
        $this->MyPageAction($userId);
    }

}
