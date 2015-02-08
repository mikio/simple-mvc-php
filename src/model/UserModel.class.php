<?php
class UserModel extends DbModel {
    // listという名前にしたかったがphp組み込みのlist関数と名前が衝突してしまう
    public function userList() {
        $sql = 'SELECT * FROM users ORDER BY id DESC';
        return $this->db->query($sql); 
    }
    public function create($data) {
        $this->db->insert('users', $data);
    }
    public function update($id, $udata) {
        $where = 'id = :id';
        $condData = array(':id' => $id);
        $this->db->update('users', $where, $condData, $udata);
    }
    // レコードの生成
    public function emptyRecord() {
        $data = array();
        $data['name'] = '';
        $data['user_id'] = '';
        $data['password'] = '';
        $data['admin'] = 0;
    }
    // レコードの取得
    public function record($id) {
        $sql = 'SELECT * FROM users WHERE id = :id';
        $rows = $this->db->query($sql, array(':id' => $id));
        $data = null;
        if (count($rows) == 0) {
            $data = $this->emptyRecord();
        } else {
            $data = $rows[0];
        }
        return $data;
    }
}
?>
