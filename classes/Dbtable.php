<?php
class Dbtable
{
    private $db = NULL;
    public $name;

    public function __construct($name = "new_table")
    {
        $this->name = $name;
        $dbConfig = [
            'server' => SERVER,
            'port' => PORT,
            'username' => USERNAME,
            'password' => PASSWORD,
            'dbname' => DBNAME,
        ];
        $this->db = new Db($dbConfig);
    }
    public function create()
    {
        $tableName = $this->name;
        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT) ;";
        $this->pdoConn->query($sql);
    }
    public function delete()
    {
        $tableName = $this->name;
        $sql = "DROP TABLE IF EXISTS " . $tableName;
        $this->db->execCommand($sql);
    }
    public function getColumns()
    {
        // get Columns
        $tableName = $this->name;
        $sql = "SHOW COLUMNS FROM `$tableName`";
        $stmt = $this->db->pdoConn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll(); // lấy kết quả trả về từ câu truy vấn
    }
    public function rename($newName)
    {
        //rename Table
        $tableName = $this->name;
        $sql = "RENAME TABLE  `$tableName` TO  `$newName`";
        $this->db->execCommand($sql);
    }
    public function addColumn($columnName, $dataType = "INT(11) UNSIGNED", $defaultValue = "")
    {
        $tableName = $this->name;
        $sql = "ALTER TABLE " . $tableName . " " . "ADD `" . $columnName . "`" . " " . $dataType;
        if (strlen($defaultValue) > 0) {
            $sql .= " DEFAULT $defaultValue";
        }
        $this->db->execCommand($sql);
    }
    public function deleteColumn($columnName)
    {
        //delete Column with $columnName
        $tableName = $this->name;
        $sql = "ALTER TABLE `" . $tableName . "` " . "DROP COLUMN `" . $columnName . "`;";
        $this->db->execCommand($sql);
    }
    public function getColumn($columnName = "")
    {
        $col = new DbColumn($columnName);
        return $col;
    }
}
