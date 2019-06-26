<?php
/**
 * 管理者に関するクラス
 *
 * ログイン機能やユーザーの編集に関するメソッドをまとめたアクションクラス
 *
 * @access public
 * @author YoshikazuSakamoto
 * @category Admin
 * @package Controller
*/
class AdminController {

    /**
     * 管理者のログインを実行
     * ユーザーの入力値とDBの値と照合する
     *
     * @public
     */
    public function SigninAction() {

        $adminId = htmlspecialchars($_POST['admin_id'], ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

        $AdminModel = new AdminModel;
        $Auth = new Auth;
        $adminData = $AdminModel->getAdminInfo($adminId);

        // パスワード認証（ハッシュ化パスワード戻す）
        if ($Auth->checkPassword($password, $adminData['password']) === true) {
            $Auth->start();
            $_SESSION['admin_flg'] = $adminData['admin_flg'];
            require_once(_VIEW_DIR . '/admin_top.html');
        } else {
            require_once(_VIEW_DIR . '/admin_signin.html');
        }
    }

    /**
     * 管理者の登録を実行
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

        $Validation = new Validation;
        $error = $Validation->validate($postData);
        if (empty($error) && isset($postData['btn_signup'])) {
            $Auth = new Auth();
            $DBModel = new DBModel();

            // パスワードのハッシュ化
            $postData['password'] = $Auth->getHashedPassword($postData['password']);
            if ($DBModel->registUser($postData) === true) {
                $pageFlg = '1';
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
                require_once(_VIEW_DIR . '/admin_top.html');
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
     * ユーザー情報を一覧表示
     *
     * @public
     */
    public function dispUserListAction() {

        $AdminModel = new AdminModel;
        $userListData = $AdminModel->getUserList();
        require_once(_VIEW_DIR . '/admin_user_list.html');
    }

    /**
     * ユーザー情報を一覧表示
     *
     * @public
     */
    public function dispUserDetailAction($userId) {

        // 更新OR削除フラグ
        $flg = 'update';

        // ユーザー情報格納
        $userData = null;

        // ユーザー削除かどうか
        if (strpos($userId, 'delete') !== false) {
            $userId = substr($userId, 6);
            $flg = 'delete';
        }

        // 数値チェック
        if (is_numeric($userId)) {
            $AdminModel = new AdminModel;
            $userData = $AdminModel->getUserDetail($userId);
        }
        if (!empty($userData)) {
            if ($flg == 'update') {
                require_once(_VIEW_DIR . '/admin_user_detail.html');
            } else {
                require_once(_VIEW_DIR . '/admin_delete_confirm_user.html');
            }
        } else {
            header('Location: http://os3-385-25562.vs.sakura.ne.jp/error');
            exit();   
        }
    }

    /**
     * ユーザー情報を修正
     *
     * @public
     */
    public function confirmUserDetailAction() {

        if (!empty($_POST)) {
            foreach($_POST as $key => $value) {
                $userData[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }
        $Validation = new Validation;
        $error = $Validation->validate($userData, $updateFlg = 1);
        if (empty($error)) {
            $BaseModel = new BaseModel;
            $uploadFile = $BaseModel->uploadFile();
            require_once(_VIEW_DIR . '/admin_confirm_user_data.html');
        } else {
            require_once(_VIEW_DIR . '/admin_user_detail.html');
        }
    }

    /**
     * ユーザー情報を修正
     *
     * @public
     */
    public function updateUserAction() {

        if (!empty($_POST)) {
            foreach($_POST as $key => $value) {
                $userData[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }
        $AdminModel = new AdminModel;
        $Validation = new Validation;
        $error = $Validation->validate($userData, $updateFlg = 1);

        $doneMessage = "更新失敗しました";
        if (empty($error)) {
            if (rename('tmp/' . $userData['user_image'], 'image/' . $userData['user_image'])) {
                if ($AdminModel->updateUserData($userData) === true) {
                    $doneMessage = "更新完了しました";
                }
            }
        }
        require_once(_VIEW_DIR . '/admin_done.html');
    }

    /**
     * ユーザー情報を削除
     *
     * @public
     */
    public function deleteUserAction() {

        if (!empty($_POST['user_id'])) {
            $userId = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8');
        }
        $AdminModel = new AdminModel;
        $doneMessage = "削除に失敗しました";
        if ($AdminModel->deleteUserData($userId)) {
            $doneMessage = "削除完了しました";
        }
        require_once(_VIEW_DIR . '/admin_done.html');
    }

    /**
     * ユーザー情報を一覧表示
     *
     * @public
     */
    public function dispAdminListAction() {

        $AdminModel = new AdminModel;
        $adminListData = $AdminModel->getAdminList();
        require_once(_VIEW_DIR . '/admin_admin_list.html');
    }

}

