<?php

// モデル。継承して使う。
abstract class DbModel extends Model {
    protected $db;
    public function __construct() {
        $this->db = new Database();
    }
}
?>
