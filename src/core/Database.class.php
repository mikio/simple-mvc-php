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

    private function getType($val) {
        $valType = gettype($val);
        $type = PDO::PARAM_STR;
        if ($valType == 'integer') {
            $type = PDO::PARAM_INT;
        }
        return $type;
    }

    public function query($sql, array $params = array()) {
        try {
            $stmt = $this->dbh->prepare($sql);
            if ($params != null) {
                foreach ($params as $key => $val) {
                    $type = $this->getType($val);
                    $stmt->bindValue($key, $val, $type);
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
                $values[] = ':'.$key;
            }
            $sql = sprintf(
                "INSERT INTO %s (%s) VALUES (%s)", 
                $tableName,
                implode(',', $fields),
                implode(',', $values)
            );
            $this->log->debug('insert:'.$sql);
            $stmt = $this->dbh->prepare($sql);
            foreach ($data as $key => $val) {
                $stmt->bindValue(':'.$key, $val);
            }
            $res  = $stmt->execute();
        } catch (PDOException $e) {
            $this->log->err('DB Error:'.$e->getMessage());
            $rows = array();
        }
        return $res;        
    }

    public function update($tableName, $where, $cdata, $udata) {
        try {
            $sql_format = "UPDATE %s SET %s WHERE %s";

            $data = array();
            $sets = '';
            foreach ($udata as $key => $val) {
                $sets .= $key.'= :'.$key.',';
                $data[':'.$key] = $val;
            }
            $sets = rtrim($sets, ',');

            $sql = sprintf($sql_format, $tableName, $sets, $where);
            get_log()->debug('updateSQL:'.$sql);

            foreach ($cdata as $key => $val) {
                $data[$key] = $val;
            }
            get_log()->debug('update data:'.p($data));

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute($data);
            $res = $stmt->execute();
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
                    $stmt->bindValue($key, $val);
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
