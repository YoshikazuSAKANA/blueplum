<?php
/**
 * TOP画面に関するアクションをまとめたクラス
 *
 * @author Yoshikazu Sakamoto
 * @category TOP
 * @package Controller
 */
class IndexController {

    /**
     * TOP画面を表示する
     *
     * @access public
     */    
    public function indexAction() {

        require_once(_VIEW_DIR . '/top.html');
    }
}
