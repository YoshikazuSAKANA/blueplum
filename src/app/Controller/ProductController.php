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

        $Rakuten = new Rakuten();
        $author = mb_convert_kana(htmlspecialchars($_POST['author'], ENT_QUOTES, 'UTF-8'), 's');
        $rakutenItem = $Rakuten->getAuthorBooks($author);
        require_once(_VIEW_DIR . '/search_item.php');
    }

    public function searchItemAction() {

      $item = htmlspecialchars($_POST['item'], ENT_QUOTES, 'UTF-8');
      $Yahoo = new Yahoo();
      $Rakuten = new Rakuten();
      $itemYahoo   = $Yahoo->searchItem($item);
      $rakutenItem = $Rakuten->searchItem($item);
      require_once(_VIEW_DIR . '/search_item.php');
    }
}
