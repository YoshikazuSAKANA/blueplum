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
}
