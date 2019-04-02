<?php

class Dispatcher {

    public function dispatch($conf) {

        // ユーザーの入力したURLを取得
        $url = $_SERVER['REQUEST_URI'];

        // リクエストがGETであればリダイレクト
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $container = array();
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
            $controllerInstance->$action($container);
        } else {
            die;
        }
    }

    public function getClassName($path) {

        // クラス名
        $className;

        $path = rtrim($path, '/');
        $path = explode('/', $path);

        // class取得
        if (count($path) > 1 && end($path) != 'index' && end($path) != 'index.php') {
            $className = $path[1];
        } elseif ((count($path) == '1') || (count($path) == '2' && end($path) == 'index.php')) {
            $className = 'index';
        }
        $className = ucfirst(strtolower($className)) . 'Controller';

        return $className;
    }

}
