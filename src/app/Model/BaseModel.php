<?php
/**
 * Description of BaseModel
 *
 * @author Yoshikazu Sakamoto
 */
class BaseModel {

    protected $logger;

    public function __construct($logger) {

        $this->logger = $logger;
    }

    public function writeAccessLog($ip, $func = "") {

        // アクセス時刻
        $time = date("Y/m/d H:i");

        // リファラ
        $referer = !empty(getenv("HTTP_REFERER")) ?  getenv("HTTP_REFERER") : "NO_Referer";

        // ログ本文
        $log = "{$time},  {$ip},  {$referer}" . PHP_EOL;

        // ログ書き込み
        $fileName = "/home/y/share/pear/blueplum/log/access.log";
        $fp = fopen($fileName, "a");
        fputs($fp, $log);
        fclose($fp);

        if (!empty($func)) {
            call_user_func($func);
        }
    }

    public static function basemodel_callback_func() {

        echo "BaseModel is back!!";
    }

}
