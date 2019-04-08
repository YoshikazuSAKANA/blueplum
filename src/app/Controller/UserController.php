<?php

class UserController {

    public function PageLoadAction() {

        require_once(_VIEW_DIR . '/signin.html');
    }

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

    public function SignupAction() {

        $pageFlg = '0';
        $postData = array();
        $error = array();

        if (!empty($_POST)) {
            foreach($_POST as $key => $value) {
                $postData[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }
        if (isset($postData['btn_confirm'])) {
            $error = $this->validation($postData);
        }
        if (empty($error)) {
            $DBModel = new DBModel();
            if ($DBModel->registUser($requestData) === true) {
                $pageFlg = '1';
                require_once(_VIEW_DIR . '/top.html');
            }
        }
        if ($pageFlg == '0') {
            print_r($error);
            require_once(_VIEW_DIR . '/signup.html');
        }
    }

    public function LogoutAction() {

        $auth = new auth();
        $auth->logout();

        require_once(_VIEW_DIR . '/top.html');
    }

    public function MyPageAction($userId) {
        $DBModel = new DBModel();
        $userData = $DBModel->getUserInfo($userId, 'id');

        require_once(_VIEW_DIR . '/mypage.html');
    }

    public function validation($data) {

        $error = array();

        if (empty($data['first_name'])) {
            $error[] = '名前を入力してください';
        }

        if (empty($data['mail_address'])) {
            $error[] = 'メールアドレスを入力してください';
        }
        return $error;  
    }
}
