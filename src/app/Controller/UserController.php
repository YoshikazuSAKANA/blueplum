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
     * ユーザーのログインを実行
     * メールアドレス、パスワードをチェックします
     *
     * @access public
     */
    public function SigninAction() {

        $inputMailAddress = htmlspecialchars($_POST['mail_address'], ENT_QUOTES, 'UTF-8');
        $inputPassword = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

        $DBModel = new DBModel();
        $userData = $DBModel->getUserInfo($inputMailAddress);
        if ($inputMailAddress === $userData['mail_address'] && $inputPassword === $userData['password']) {
            $auth = new auth();
            $auth->start();
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_name'] = $userData['first_name'];
            require_once(_VIEW_DIR . '/top.html');
        } else {
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
        $pageFlg;

        // ユーザー入力値
        $postData = array();

        // エラー文
        $error = array();

        if (!empty($_POST)) {
            foreach($_POST as $key => $value) {
                $postData[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }

        $Validation = new Validation();
        // レスポンス値を整形する
        $postData = $Validation->formatPostData($postData);
        // レスポンス値(整形後)のバリデーション
        $error = $Validation->validationPostData($postData);

        if (empty($error)) {
            if (isset($postData['btn_confirm'])) {
                $pageFlg = '0';
            } elseif (isset($postData['btn_signup'])) {
                $DBModel = new DBModel();
                if ($DBModel->registUser($postData) === true) {
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
                require_once(_VIEW_DIR . '/top.html');
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

        $auth = new auth();
        $auth->logout();

        require_once(_VIEW_DIR . '/top.html');
    }

    /**
     * ユーザーのマイページを表示.
     * ユーザーidを使用して、DBから値を抽出
     *
     * @access public
     * @param int $id
     */
    public function MyPageAction($userId) {
        $DBModel = new DBModel();
        if ($userData = $DBModel->getUserInfo($userId, 'id')) {
            require_once(_VIEW_DIR . '/mypage.html');
        } else {
            require_once(_VIEW_DIR . '/error.html');
        }
    }

}
