<?php

interface WebAPI {

    public function searchItem($itemName);

}

class Item {

  public function execApi($api, $params){

      $query = http_build_query($params);
      $request = $api . $query;
      return json_decode(file_get_contents($request), true);
  }
}

class Rakuten implements WebAPI {

    CONST RAKUTEN_APP_ID = '1025653256501622754';

    public function searchItem($itemName) {

      $api = 'https://app.rakuten.co.jp/services/api/IchibaItem/Search/20170706?';
      $params = [
          'applicationId' => RAUTEN_APP_ID,
          'keyword'       => $itemName,
          'sort'          => '+affiliateRate'
      ];
      $Item = new Item();
      $items = $Item->execApi($api, $params);
      return $items;
    }

    public function getAuthorBooks($author) {

        $api = 'https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404?';
        $affiliateId = '1d009b0.0dfb50a9.18d009b1.13448471';

        $params = [
            'applicationId' => RAKUTEN_APP_ID,
            'affiliateId'   => $affiliateId,
            'author'        => $author,
            'hits'          => 30,
            'page'          => 9,
            'carrier'       => 0,
            'formatVersion' => 2,
            'format'        => 'json',
            'sort'          => 'sales'
          ];
          $Item = new Item();
          $rakutenContent = $Item->execApi($api, $params);
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

    public function searchItem($itemName) {

        $api = 'http://shopping.yahooapis.jp/ShoppingWebService/V1/itemSearch?';
        $appId = 'dj00aiZpPTJmVXg0SGhLVmVseSZzPWNvbnN1bWVyc2VjcmV0Jng9ZjY-';
        $params = [
            'appid' => $appId,
            'type'  => $author,
            'sort'  => '-sold'
        ];
        $Item = new Item();
        $item = $Item->execApi($api, $params);
        print_r($item);
    }

}
