<?php
/**
 * 商品情報に関するアクションをまとめたクラス
 *
 * @author Yoshikazu Sakamoto
 * @category Product
 * @package Controller
 */
class ProductController {

    public function searchBooksAction() {

        // POST取得
        $author = mb_convert_kana(htmlspecialchars($_POST['author'], ENT_QUOTES, 'UTF-8'), 's');
        $sort   = htmlspecialchars($_POST['sort'], ENT_QUOTES, 'UTF-8');

        // API実行結果取得
        $Rakuten = new Rakuten();
        $objItems = $Rakuten->getAuthorBook($author, $sort);

        // HTML表示タイトル
        $title = '著者の本売り上げランキング：' . $author;
        require_once(_VIEW_DIR . '/search_item.php');
    }

    public function searchItemAction() {

      $item = htmlspecialchars($_POST['item'], ENT_QUOTES, 'UTF-8');
      $Yahoo   = new Yahoo();
      $Rakuten = new Rakuten();
 //     $itemYahoo   = $Yahoo->searchItem($item);
      $objItems = $Rakuten->searchItem($item);

      $title = '商品アフィリエイト率高いランキング：' . $item;
      require_once(_VIEW_DIR . '/search_item.php');
    }
}
