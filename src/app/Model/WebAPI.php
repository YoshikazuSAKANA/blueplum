<?php

interface WebAPI {

    public function searchItem($author);

}

class Rakuten implements WebAPI {

    public function searchItem($author) {

        $api = 'https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404?';
        $applicationId = '1025653256501622754';
        $affiliateId = '1d009b0.0dfb50a9.18d009b1.13448471';

        $params = [
            'applicationId' => $applicationId,
            'affiliateId'   => $affiliateId,
            'author'        => $author,
            'hits'          => 30,
            'page'          => 9,
            'carrier'       => 0,
            'formatVersion' => 2,
            'format'        => 'json',
            'sort'          => 'sales'
          ];
          $query = http_build_query($params);
          $request = $api . $query;
          $rakutenContent = json_decode(file_get_contents($request), true);
          $rakutenItem = [];
          $i = 0;
          foreach($rakutenContent['Items'] as $itemNumber => $items) {
              $rakutenItem[$i]['title'] = $items['title'];
              $rakutenItem[$i]['image'] = $items['mediumImageUrl'];
              $i++;
          }
          return $rakutenItem;
    }


}

class Yahoo implements WebAPI {

    public function searchItem($author) {

        $api = 'http://shopping.yahooapis.jp/ShoppingWebService/V1/itemSearch?';
        $appId = 'dj00aiZpPTJmVXg0SGhLVmVseSZzPWNvbnN1bWVyc2VjcmV0Jng9ZjY-';
        $params = [
            'appid' => $appId,
            'type'  => $author,
            'sort'  => '-sold'
        ];

        $query = http_build_query($params);
        $request = $api . $query;
        $item = json_decode(file_get_contents($request));
        print_r($item);
    }

}

