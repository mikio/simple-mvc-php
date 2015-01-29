<?php

class Database {
    private $dbh = null;
    private $log = null;

    public function __construct() {
        $this->log = get_log();
        $dsn = sprintf('mysql:dbname=%s;host=%s;port=%s', DB_DATABASE, DB_HOST, DB_PORT);
        $this->log->debug('dsn:'.$dsn);
        $user = DB_USERNAME;
        $password = DB_PASSWORD;
        try {
            $this->dbh = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            $this->log->err('DB Connection failed:'.$e->getMessage());
        }
    }

    public function __destruct() {
        $this->dbh = null;
    }

    public function query($sql, array $params = array()) {
        try {
            $stmt = $this->dbh->prepare($sql);
            if ($params != null) {
                foreach ($params as $key => $val) {
                    $stmt->bindValue(':' . $key, $val);
                }
            }
            $stmt->execute();
            $rows = $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->log->err('DB Error:'.$e->getMessage());
            $rows = array();
        }
        return $rows;
    }

    public function insert($tableName, $data) {
        try {
            $fields = array();
            $values = array();
            foreach ($data as $key => $val) {
                $fields[] = $key;
                $values[] = ':' . $key;
            }
            $sql = sprintf(
                "INSERT INTO %s (%s) VALUES (%s)", 
                $tableName,
                implode(',', $fields),
                implode(',', $values)
            );
            $stmt = $this->dbh->prepare($sql);
            foreach ($data as $key => $val) {
                $stmt->bindValue(':' . $key, $val);
            }
            $res  = $stmt->execute();
        } catch (PDOException $e) {
            $this->log->err('DB Error:'.$e->getMessage());
            $rows = array();
        }
        return $res;        
    }

    public function delete($where, $params = null) {
        try {
            $sql = sprintf("DELETE FROM %s", $this->name);
            if ($where != "") {
                $sql .= " WHERE " . $where;
            }
            $stmt = $this->dbh->prepare($sql);
            if ($params != null) {
                foreach ($params as $key => $val) {
                    $stmt->bindValue(':' . $key, $val);
                }
            }
            $res = $stmt->execute();
        } catch (PDOException $e) {
            $this->log->err('DB Error:'.$e->getMessage());
            $rows = array();
        }
        return $res;
    }
}
?>
