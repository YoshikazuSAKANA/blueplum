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
        // レスポンス値を整形する
        $postData = $this->formatPostData($postData);
        // レスポンス値(整形後)のバリデーション
        $error = $this->validation($postData);

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
        $userData = $DBModel->getUserInfo($userId, 'id');

        require_once(_VIEW_DIR . '/mypage.html');
    }

    /**
     * 登録フォーム画面のレスポンチ値をバリデーションのために整形
     *
     * @access public
     * @param array $postData
     * @return array $postData
     */
    public function formatPostData($postData) {

        // 姓の空白を削除
        if (!empty($postData['last_name'])) {
            $postData['last_name'] = str_replace(array(' ','　'), '', $postData['last_name']);
        }

        // 名前の空白を削除
        if (!empty($postData['first_name'])) {
            $postData['first_name'] = str_replace(array(' ','　'), '', $postData['first_name']);
        }

        // 生年月日を半角に変換
        if (!empty($postData['birthday'])) {
            $postData['birthday'] = mb_convert_kana($postData['birthday'], 'n');
        }

        // メールアドレスを半角に変換
        if (!empty($postData['mail_address'])) {
            $postData['mail_address'] = mb_convert_kana($postData['mail_address'], 'a');
        }

        // パスワードを半角に変換
        if (!empty($postData['password'])) {
            $postData['password'] = mb_convert_kana($postData['password'], 'a');
        }
        return $postData;
    }

    /**
     * 登録フォームのレスポンス値(整形後)をバリデーション.
     * 処理ごとにエラー文を格納
     *
     * @access public
     * @param array $data
     * @return array $data
     */
    public function validation($data) {

        // エラー文
        $error = array();

        // 同一のメールが存在するか
        $DBModel = new DBModel();

        // 姓チェック
        if (empty($data['last_name'])) {
            $error[] = '姓を入力してください';
        } else if (mb_strlen($data['last_name'] > 15)) {
            $error[] = '姓を15文字以内にしてください';
        }

        // 名前チェック
        if (empty($data['first_name'])) {
            $error[] = '名前を入力してください';
        } else if (mb_strlen($data['first_name'] > 15)) {
            $error[] = '名前を15文字以内にしてください';
        }

        // 生年月日チェック
        if (empty($data['birthday'])) {
            $error[] = '生年月日を入力してください';
        } else {
            list($y, $m, $d) = explode('/', $data['birthday']);
            if (!checkdate($m, $d, $y)) {
                $error[] = '生年月日が存在しません';
            } elseif ($y < 1900) {
                $error[] = '生年月日が正しくありません';
            }
        }

        // メールアドレスチェック
        if (empty($data['mail_address'])) {
            $error[] = 'メールアドレスを入力してください';
        } elseif (!preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', $data['mail_address'])){
            $error[] =  'メールアドレスが正しくありません';
        } elseif ($DBModel->checkExistMailAddress($data['mail_address']) === false) {
            $error[] = 'すでに同一のメールアドレスが存在します';
        }

        // パスワードチェック
        if (empty($data['password'])) {
            $error[] = 'パスワードを入力してください';
        } elseif (mb_strlen($data['password']) > 100) {
            $error[] = 'パスワードは100文字以内にしてください';
        } elseif (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $data['password'])) {
            $error[] = '英数字を含む8文字以上のパスワードにしてください';
        }
        return $error;  
    }

}
