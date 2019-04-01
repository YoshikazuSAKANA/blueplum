<?php

class IndexController {
    
    public function indexAction() {

        $DBModel = new DBModel();
        $products = $DBModel->searchALL();

//print_r($products);
        require_once(_VIEW_DIR . '/top.html');
    }
}
