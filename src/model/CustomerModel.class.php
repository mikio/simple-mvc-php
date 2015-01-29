<?php
class CustomerModel extends DbModel {
    private $log = null;
    // public function __construct() {
    //     //$this->log = get_log();
    // }
    public function lists() {
        $sql = 'SELECT * FROM customers ORDER BY id DESC';
        return $this->db->query($sql); 
    }
    public function create($data) {
        $this->db->insert('customers', $data);
    }
}
?>
