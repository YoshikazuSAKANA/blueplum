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
        $rakutenItem = $Rakuten->searchItem($author);
        require_once(_VIEW_DIR . '/search_book.php');
    }

    public function searchItemAction() {

      $item = htmlspecialchars($_POST['item'], ENT_QUOTES, 'UTF-8');
      $Yahoo = new Yahoo();
      $Yahoo->searchItem($item);
      require_once(_VIEW_DIR . '/search_book.php');
    }
}
