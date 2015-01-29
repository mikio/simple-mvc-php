<?php

// モデル。継承して使う。
abstract class DbModel extends Model {
    protected $db;
    public function __construct() {
        get_log()->debug("db1");
        $this->db = new Database();
        get_log()->debug("db2");
    }
}
?>
