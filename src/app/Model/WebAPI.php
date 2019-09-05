<?php

interface WebAPI {

    public function searchItem($itemName);

}

class Rakuten implements WebAPI {

    public function searchItem($itemName) {

      $api = 'https://app.rakuten.co.jp/services/api/IchibaItem/Search/20170706?';
      $params = [
          'applicationId' => _RAKUTEN_APP_ID,
          'keyword'       => $itemName,
          'sort'          => urlencode('standard')
      ];
      // API実行
      $response = execApi($api, $params);

      foreach($response['Items'] as $items => $itemNumber) {
          $item[] = new Item(
              $itemNumber['Item']['itemName'],
              $itemNumber['Item']['itemPrice'],
              $itemNumber['Item']['mediumImageUrls'][0]['imageUrl'],
              'rakuten'
          );
      }
      return $item;
    }

    public function getAuthorBook($author, $sort) {

        $api = 'https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404?';

        $params = [
            'applicationId' => _RAKUTEN_APP_ID,
            'affiliateId'   => _RAKUTEN_AFL_ID,
            'author'        => $author,
            'hits'          => 30,
            'page'          => 9,
            'carrier'       => 0,
            'formatVersion' => 2,
            'format'        => 'json',
            'sort'          => urlencode($sort)
        ];
        $response = execApi($api, $params);

        foreach($response['Items'] as $items => $itemNumber) {
            $item[] = new Item(
              $itemNumber['title'],
              $itemNumber['itemPrice'],
              $itemNumber['mediumImageUrl'],
              'rakuten'
            );
        }
        return $item;
    }

}

class Yahoo implements WebAPI {

    public function searchItem($itemName) {

        $api = 'https://shopping.yahooapis.jp/ShoppingWebService/V1/json/itemSearch?';
        $params = [
            'appid'  =>_YAHOO_APP_ID,
            'query'  => $itemName,
            'hits'   => 2
        ];
        
        $response = execApi($api, $params);
        return $item;
    }
}

class Item {

    public $name;
    public $price;
    public $image;
    public $site;

    public function __construct($name, $price, $image, $site) {

        $this->name  = $name;
        $this->price = $price;
        $this->image = $image;
        $this->site  = $site;
    }

}
