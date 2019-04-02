<?php

class IndexController {
    
    public function indexAction() {

        $DBModel = new DBModel();
        $products = $DBModel->searchALL();

        require_once(_VIEW_DIR . '/top.html');
    }
}
