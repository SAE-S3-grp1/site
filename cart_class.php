<?php
class cart {

    public function __construct(){

        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart']=array();
        }
    }

    public function add ($product_id) {
        $_SESSION['cart'][$product_id] = 1;
        var_dump($_SESSION['cart']);
    }

    public function del ($product_id) {
        unset($_SESSION['cart'][$product_id]);
    }

}