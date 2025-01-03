<?php
class cart {

    private $db;

    public function __construct($db){

        if(!isset($_SESSION)){
            session_start();
        }

        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart']=array();
        }

        $this->db = $db;

        if(isset($_POST['cart']['quantity'])) {
            $this->recalc();
        }
    }

    public function recalc () {
        //$_SESSION['cart'] = $_POST['cart']['quantity'];

        foreach($_SESSION['cart'] as $product_id => $quantity) {
            if(isset($_POST['cart']['quantity'][$product_id])) {
                $_SESSION['cart'][$product_id]= $_POST['cart']['quantity'][$product_id];
            }
        }
    }

    public function total(){
        $total = 0;

        $ids = array_keys($_SESSION['cart']);
        if(empty($ids)){
            $products = array();
        }
        else {
            $placeholders = implode(",", array_fill(0, count($ids), "?"));
            $query = "SELECT id_article, prix_article FROM ARTICLE WHERE id_article IN ($placeholders)";
            $types = str_repeat("i", count($ids));
            
            $products = $this->db->select(
                $query, 
                $types, 
                $ids
            );
        }

        foreach ($products as $product){
            $total+= $product['prix_article'] * $_SESSION['cart'][$product['id_article'] ];
        }
        return $total;
    }

    public function count () {
        return array_sum($_SESSION['cart']);
    }

    public function add ($product_id) {
        if(isset($_SESSION['cart'][$product_id])){
            $_SESSION['cart'][$product_id]++;
        }
        else{
            $_SESSION['cart'][$product_id] = 1;
        }
    }

    public function del ($product_id) {
        unset($_SESSION['cart'][$product_id]);
    }

}