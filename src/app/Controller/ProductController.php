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
     * @param int $id
     */
    public function dispCarDetailAction($id) {

        $DBModel = new DBModel();
        $product = $DBModel->searchProductDetail($id);

        require_once(_VIEW_DIR . '/detail.html');
    }
}
