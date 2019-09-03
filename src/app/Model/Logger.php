<?php

interface LoggerInterface {

    public function log($message);

}

class FileLogger  implements LoggerInterface {

    protected $file;

    public function __construct($filePath) {

        $this->file = new SplFileObject($filePath, 'a');
    }

    // インターフェイスの定義と一致
    public function log($message) {

        $this->file->fwrite($message . PHP_EOL);
    }
}

class DatabaseLogger extends DBModel implements LoggerInterface {

    public function __construct() {
        
        parent::dbConnect();
    }

    // インターフェイスの定義と一致
    public function log($message) {

        $stmt = $this->pdo->prepare(
            'INSERT INTO log (message) VALUES (:message)');
        $stmt->bindValue(':message', $message);
        $stmt->execute();
    }
}
