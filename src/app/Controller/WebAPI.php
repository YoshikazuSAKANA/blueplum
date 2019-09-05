<?php

interface API {

    public function searchRakutenItem();

    public function searchZipCodeAction($zipCode);

    public function returnJson($resultArray);

}

class WebAPI implements API {

    public function searchZipCodeAction($zipCode) {

        // ログ出力ファイル
        $logger = new FileLogger('/home/y/share/pear/blueplum/log/api.log');

        // 返却値の初期化
        $result = [];

        try {
          if (empty($zipCode)) {
              throw new Exception("no zipcode...");
          }
          $BaseModel = new BaseModel($logger);
          $address = $BaseModel->getUserAddress($zipCode);
          if (!empty($address)) {
              $result = [
                'result'  => '200',
                'address' => $address
              ];
          }
        } catch(Exception $e) {
            $result = [
              'result'  => '200',
              'message' => $e->getMessage()
              ];
        }
        $this->returnJson($result);
    }

    public function returnJson($resultArray) {

        echo json_encode($resultArray);
        exit;
    }

}
