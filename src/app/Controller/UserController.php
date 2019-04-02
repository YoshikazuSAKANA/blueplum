<?php

class UserController {

    public function PageLoadAction() {

        require_once(_VIEW_DIR . '/signin.html');
    }

    public function SigninAction() {

        $inputMailAddress = $_POST['mail_address'];
        $inputPassword = $_POST['password'];

        $DBModel = new DBModel();
        $userData = $DBModel->getUserInfo($inputMailAddress);
        if ($inputMailAddress === $userData['mail_address'] && $inputPassword === $userData['password']) {
            $auth = new auth();
            $auth->setAuthname('_USER_INFO');
            $auth->setSessname('_MEMBER_SESSID');
            $auth->start();
            $_SESSION[$auth->getAuthname] = $userData;

            require_once(_VIEW_DIR . '/top.html');
        } else {
            require_once(_VIEW_DIR . '/signin.html');
        }
    }

    public function SignupAction() {

        $input = $_REQUEST;
        $DBModel = new DBModel();

        if ($DBModel->registUser($input)) {
            require_once(_VIEW_DIR . '/top.html');
        } else {
            require_once(_VIEW_DIR . '/signup.html');
        }

    }

    public function LogoutAction() {

        $auth = new auth();
        $auth->logout();

        require_once(_VIEW_DIR . '/top.html');
    }

}
