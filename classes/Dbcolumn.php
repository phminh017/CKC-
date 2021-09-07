<?php
class DbColumn
{
    public $tableName;
    public $name;

    public $field;
    public $type;
    public $null;
    public $key;
    public $default;
    public $extra;

    function __construct($tableName = "", $columnName = "")
    {
        $this->tableName = $tableName;
        $this->name = $columnName;
        $this->load();
    }
    public function load()
    {
        $table = new Dbtable($this->tableName);
        $columns = $table->getColumns();
        foreach ($columns as $column) {
            if (strcmp($column['Field'], $this->name) == 0) {
                $this->field = $column['Field'];
                $this->type = $column['Type'];
                $this->null = $column['Null'];
                $this->key = $column['Key'];
                $this->default = $column['Default'];
                $this->extra = $column['Extra'];
                break;
            }
        }
    }

    public function rename($newName = "")
    {
        $tableName = $this->tableName;
        $columnName = $this->name;
        $sql = "ALTER TABLE `$tableName` RENAME COLUMN `$columnName` TO $newName";
        $dbConfig = [
            'server' => SERVER,
            'port' => PORT,
            'username' => USERNAME,
            'password' => PASSWORD,
            'dbname' => DBNAME,
        ];
        $db = new Db($dbConfig);
        $db->execCommand($sql);

        // ALTER TABLE table_name RENAME COLUMN old_col_name TO new_col_name;
        //         ALTER TABLE table_name
        // MODIFY COLUMN column_name datatype; 

    }
    public function changeDatatype($newDatatype)
    {
        $tableName = $this->tableName;
        $columnName = $this->name;
        $sql = "ALTER TABLE `$tableName` MODIFY COLUMN `$columnName` $newDatatype";
        $dbConfig = [
            'server' => SERVER,
            'port' => PORT,
            'username' => USERNAME,
            'password' => PASSWORD,
            'dbname' => DBNAME,
        ];
        $db = new Db($dbConfig);
        $db->execCommand($sql);
    }
}
