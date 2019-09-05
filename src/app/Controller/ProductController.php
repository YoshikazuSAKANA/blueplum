<?php
/**
 * 商品情報に関するアクションをまとめたクラス
 *
 * @author Yoshikazu Sakamoto
 * @category Product
 * @package Controller
 */
class ProductController {

    /**
     * 商品詳細を抽出＆表示.
     *
     * @access public
     * @param int $carId
     */
    public function dispCarDetailAction($carId) {

        $DBModel = new DBModel();
        $product = $DBModel->searchProductDetail($carId);

        require_once(_VIEW_DIR . '/detail.html');
    }

    public function searchBooksAction() {

        $Rakuten = new Rakuten();
        $author = mb_convert_kana(htmlspecialchars($_POST['author'], ENT_QUOTES, 'UTF-8'), 's');
        $rakutenItem = $Rakuten->searchItem($author);
        require_once(_VIEW_DIR . '/search_book.php');
    }
}
