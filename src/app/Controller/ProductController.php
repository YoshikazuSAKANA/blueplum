<?php

class ProductController {

    public function dispCarDetailAction($id) {

        $DBModel = new DBModel();
        $product = $DBModel->searchProductDetail($id);

        require_once(_VIEW_DIR . '/detail.html');
    }
}
