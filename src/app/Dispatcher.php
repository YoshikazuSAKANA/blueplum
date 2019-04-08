<?php

class Dispatcher {

    public function dispatch($conf) {

        // ユーザーの入力したURLを取得
        $url = $_SERVER['REQUEST_URI'];

        // リクエストがGETであればリダイレクト
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // URLのパラメーター
        $param = null;

        // コンフィグURLのチェック
        $chkUrl = false;

        for ($i = 0;$i < count($conf); $i) {
            if ($conf[$i][0] == $requestMethod) {
                if ($conf[$i][1] == $url && $conf[$i][3] == 'PageLoadAction') {
                    require_once($conf[$i][4]);
                    exit();
                }
                if (strpos($conf[$i][1], ':')) {
                    $paramStartNumber = strpos($conf[$i][1], ':');
                    if ((substr($conf[$i][1], 0, $paramStartNumber)) == substr($url, 0, $paramStartNumber)) {
                        $param = substr($url, $paramStartNumber, strlen($url));
                        $chkUrl = true;
                        break; 
                    }
                } else {
                    if ($conf[$i][1] == $url) {
                        $chkUrl = true;
                        break;
                    }
                }
                    
            } $i++;
        }

        if ($chkUrl === true) {
            $className = $conf[$i][2];
            $action = $conf[$i][3];
            $controllerFile = _CONTROLLER_DIR . '/' . $className . '.php';
        } else {
            echo "URLが間違っています。";
            die;
        }
        if (file_exists($controllerFile)) {

            //コントローラ呼び出し
            require_once($controllerFile);
            $controllerInstance = new $className();
            $controllerInstance->$action($param);
        } else {
            exit();
        }
    }

}
