<?php
class Db
{
    public $pdoConn = NULL;

    function __construct($dbConfig = [])
    {
        try {
            $this->pdoConn = (new Connection($dbConfig))->createPDOConnection();
        } catch (PDOException $e) {
            die("Database Connection failed: " . $e->getMessage());
        }
    }
    function querySql($sql = "")
    {
        // Dùng để truy vấn đến cơ sở dữ liệu
        // Và có nhận giá trị trả về
        $stmt = $this->pdoConn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll(); // SELECT only - lấy kết quả trả về
    }
    function execCommand($sql = '')
    {
        // Dùng để thực thi câu lệnh cơ sở dữ liệu
        // nhưng không cần giá trị trả về
        $stmt = $this->pdoConn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute(); // Không cần lấy kết quả trả về
    }
    function createTable($tableName = '')
    {
        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT) ;";
        $this->pdoConn->exec($sql);
        // $this->pdoConn->query($sql);
    }
    function dropTable($tableName = "")
    {
        $sql = "DROP TABLE IF EXISTS `" . $tableName . "`";
        $this->pdoConn->exec($sql);
    }

    // function update($sqlUppdate = "")
    // {
    //     $this->pdoConn->exec($sqlUppdate);

    //     // $stmt = $this->pdoConn->prepare($sqlUppdate);
    //     // $stmt->setFetchMode(PDO::FETCH_ASSOC);
    //     // $stmt->execute();
    // }
    // function select($sql = '')
    // {
    //     // return $this->pdoConn->query($sql);
    //     $stmt = $this->pdoConn->prepare($sql);
    //     $stmt->setFetchMode(PDO::FETCH_ASSOC);
    //     $stmt->execute();
    //     return $stmt->fetchAll(); // SELECT only - lấy kết quả trả về
    // }
    function insertInto($sql = '')
    {
        $stmt = $this->pdoConn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $lastInsertId = $this->pdoConn->lastInsertId();
        return $lastInsertId; // Trả về id của dòng mới được thêm
    }
    function getTableNames()
    {
        // Lấy tất cả tableName có trong cơ sở dữ liệu
        // kết quả trả về dạng mảng các tableName
        $sql = "SHOW TABLES";
        $stmt = $this->pdoConn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $temp = $stmt->fetchAll(); // lấy kết quả trả về từ câu truy vấn
        $tableNames = [];
        foreach ($temp as $key => $value) {
            $tableNames[] = array_values($value)[0];
        }
        return $tableNames;
    }
    function execSqlFile($sqlFile = '')
    {
        // Thực thi một file chứa các câu lệnh sql
        $file = $sqlFile;
        if (file_exists($file)) {
            $f = fopen($file, 'r');
            if ($f) {
                while (!feof($f)) {
                    $sQuery = '';
                    $c = fgetc($f);
                    while ($c !== ';' && !feof($f)) {
                        $sQuery .= $c;
                        $c = fgetc($f);
                    }
                    if (strlen($sQuery) > 10 && $sQuery !== '') {
                        $sQuery .= '';
                        $this->execCommand($sQuery);
                    }
                }
            }
            fclose($f);
        }
    }
}
