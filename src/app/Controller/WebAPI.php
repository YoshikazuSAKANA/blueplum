<?php

interface API {

    public function searchZipCodeAction($zipCode);

    public function returnJson($resultArray);

}

class WebAPI implements API {

    public function searchItem() {

        $api = 'https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404';
        $applicationId = '1025653256501622754';
        $affiliateId = '1d009b0.0dfb50a9.18d009b1.13448471';
        $params = [
            'applicationId' => $applicationId,
            'affiliateId'   => $affiliateId,
            'author'        => $keyword,
            'hits'          => 30,
            'page'          => 9,
            'carrier'       => 0,
            'formatVersion' => 2,
            'format'        => 'json',
            'sort'          => 'sales'
          ]
          $query = http_biuld_quety($params);
          $request = $api . '?' . $query;
          $response = file_get_contents($request);
          $reult = json_deconde($response);
          return $result;
        }

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

    public function curlAction() {

        $result = $_REQUEST;
        echo "OK";
        //return "TEST";
    }
}
