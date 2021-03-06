<?php
class Model
{
    public $data = [];

    private $select = "";
    private $from = "";
    private $where = "";
    private $offset = 1;
    private $limit = 10;
    private $orderBy = "";

    //findOne
    public static function findOne($id = 1)
    {
        $tableName = static::tableName();
        $object = NULL;
        $sql = "SELECT * FROM `$tableName` WHERE id=$id; ";
        $resultArray = App::$db->querySql($sql);
        if (count($resultArray) > 0) {
            $object = new $tableName;
            $object->select = "*";
            $object->from = $tableName;
            $object->where = "id=$id";
            $object->data = $resultArray[0]; //get only one Object
            // $object->data = App::$db->querySql($sql); //get Big Object = [Object, Oject,....]
        }
        return $object;
    }
    //findAll
    public static function findAll($conditions = [])
    {
        $tableName = static::tableName();
        $sql = "SELECT * FROM `$tableName`;";
        if (count($conditions) > 0) {
            $strConditions = "";
            $i = 0;
            $count = count($conditions);
            foreach ($conditions as $key => $value) {
                $strConditions .= "$key=$value";
                $i++;
                if ($i < $count) {
                    $strConditions .= " and ";
                }
            }
            $sql = "SELECT * FROM `$tableName` WHERE $strConditions; ";
        }

        $objectArray = [];

        $models = App::$db->querySql($sql);
        if (count($models) > 0) {
            $objectArray = [];
            foreach ($models as $model) {
                $object = new $tableName;
                $object->data = $model;
                $objectArray[] = $object;
            }
        }
        return $objectArray;
    }
    //find
    public static function find()
    {
        $tableName = static::tableName();
        $object = new $tableName;
        $object->select = "*";
        $object->from = $tableName;
        return $object;
    }
    function where($conditions = [])
    {
        $this->where = "";
        $count = count($conditions);
        if ($count > 0) {
            $where = "";
            $i = 0;
            foreach ($conditions as $key => $value) {
                if (is_string($value)) {
                    $where .= "`$key` like '$value'";
                } else {
                    $where .= "`$key`=$value";
                }
                $i++;
                if ($i < $count) {
                    $where .= " and ";
                }
            }
            $this->where = $where;
        }
        return $this;
    }
    function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
    function orderBy($orderByArray = [])
    {
        // $orderBy = [$attr1 => SORT_ASC/SORT_DESC, $attr2 => ASC/DESC, $attr3] : default ASC
        $this->orderBy = "";
        if (count($orderByArray) > 0) {
            $orderBy = "";
            $i = 0;
            $count = count($orderByArray);
            foreach ($orderByArray as $key => $value) {
                if (is_numeric($key)) {
                    $attr = $value;
                    $orderBy .= "`$attr`";
                } else {
                    $attr = $key;
                    $orderBy .= ($value == SORT_DESC) ? "`$attr` DESC" : "`$attr` ASC";
                }
                $i++;
                if ($i < $count) {
                    $orderBy .= ", ";
                }
            }
            $this->orderBy = $orderBy;
        }
        return $this;
    }
    function all()
    {
        // select, from, where, orderBy; no limit, no offset
        $select = $this->select;
        $from = $this->from;
        $where = $this->where;
        $orderBy = (strlen($this->orderBy) > 0) ? "ORDER BY $this->orderBy" : "";

        $sql = (strlen($where) > 0) ?
            "SELECT $select FROM $from WHERE $where $orderBy;" :
            "SELECT $select FROM $from $orderBy;";

        $models = App::$db->querySql($sql);

        $tableName = static::tableName();
        $objectArray = [];
        foreach ($models as $model) {
            $object = new $tableName;
            $object->data = $model;
            $objectArray[] = $object;
        }
        return $objectArray;
    }
    //get one
    function one()
    {
        $select = $this->select;
        $from = $this->from;
        $where = $this->where;
        $sql = "SELECT $select FROM $from WHERE $where ;";

        $tableName = static::tableName();
        $object = new $tableName;
        $object->data = App::$db->querySql($sql)[0]; //ch??? l???y 01 ?????i t?????ng
        return $object;
    }
    //count 
    function count()
    {
        $from = $this->from;
        $where = $this->where;

        $sql = (strlen($where) > 0) ?
            "SELECT count(*) FROM $from WHERE $where ;" :
            "SELECT count(*) FROM $from ;";

        $count = App::$db->querySql($sql)[0]["count(*)"]; // l???y gi?? tr??? count(*)
        return $count;
    }

    //Save
    public function save()
    {
        if ($this->isExists()) {
            // trong tr?????ng h???p ???? c?? trong table th?? c???p nh???t l???i gi?? tr???
            // v?? l??u c??c gi?? tr??? thu???c t??nh xu???ng table
            foreach ($this->data as $attrName => $value) {
                $this->updateAttr($attrName, $value); //L??u t???ng gi?? tr??? xu???ng table
            }
        } else {
            // trong tr?????ng h???p ch??a c?? trong table
            // th?? t???o m???i v?? insert into v??o table 
            $this->insertInto();
        }
    }

    //Delete
    public function delete()
    {
        $tableName = static::tableName();
        $id = $this->id;
        $sql = "DELETE FROM `$tableName` WHERE id=$id;";
        App::$db->execCommand($sql);
    }
    //setAttributes
    public function setAttributes($values = [], $safeOnly = true)
    {
        // $values = [$name=>$value]
        foreach ($values as $name => $value) {
            $this->data[$name] = $value;
        }
    }
    //update All
    static function updateAll($attributes = [], $conditions = [])
    {
        $objects = self::findAll($conditions);
        $count = 0;
        foreach ($objects as $object) {
            foreach ($attributes as $key => $value) {
                $object->updateAttr($key, $value);
            }
            $count++;
        }
        // Tr??? v??? s??? d??ng ???????c update
        return $count;
    }
    //uppdate Attr
    function updateAttr($attrName = "", $value = "")
    {
        //C???p nh???t gi?? tr??? c???a m???t thu???c t??nh c???a m???t d??ng trong table
        $tableName = static::tableName();
        $id = $this->id;
        $setUpdate = "`$attrName` = '" . $value . "'";
        $sql = "UPDATE $tableName SET $setUpdate WHERE id = $id; ";
        App::$db->execCommand($sql);
    }
    //Insert into
    function insertInto()
    {
        $tableName = static::tableName();
        // Th??m m???t model v??o table v???i c??c gi?? tr??? m???c ?????nh c???a thu???c t??nh 
        $sql = "INSERT INTO `$tableName` (`id`) VALUES (NULL); ";

        // Insert into v?? l???y id c???a d??ng m???i v???a ???????c th??m t??? table
        $this->id = App::$db->insertInto($sql);

        //Update gi?? tr??? c??c thu???c t??nh v??o d??ng m???i v???a th??m
        foreach ($this->data as $attrName => $value) {
            if (strcmp($attrName, "id") == 0) continue;
            // L??u l???i t???ng gi?? tr??? c???a m???i thu???c t??nh v??o table
            $this->updateAttr($attrName, $value);
        }
    }

    // isExists: Ki???m tra model hi???n t???i ???? c?? trong table hay ch??a ?
    function isExists()
    {
        $id = 0;
        if (array_key_exists("id", $this->data)) {
            $id = $this->data['id'];
        };
        $model = self::findOne($id);
        return (!is_null($model));
    }

    // Query:  h??m n??y ???????c d??ng c??ng v???i th???c hi???n 
    // select, from, where, orderBy and limit, offset
    function query()
    {
        $select = $this->select;
        $from = $this->from;
        $where = $this->where;
        $offset = $this->offset;
        $limit = $this->limit;
        $sqlCommand =  (strlen($where) > 0) ?
            "SELECT $select FROM $from WHERE $where LIMIT $limit OFFSET $offset; " :
            "SELECT $select FROM $from LIMIT $limit OFFSET $offset; ";

        $models = App::$db->querySql($sqlCommand);

        $tableName = static::tableName();
        $objectArray = [];
        foreach ($models as $model) {
            $object = new $tableName;
            $object->data = $model;
            $objectArray[] = $object;
        }
        return $objectArray;
    }
    static function getColumnNames()
    {
        // $attrs = [];
        // $tableName = static::tableName();
        // $columns =   App::$db->getTableColumns($tableName);
        // foreach ($columns as $column) {
        //     $attrs[] = $column['Field'];
        // }
        // return $attrs;
    }
    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public static function tableName()
    {
        // M???i model s??? t????ng t??c v???i m???i table c??? th???
        // V?? d??? model Article s??? t????ng t??c v???i article table
        // Do v???y, Class k??? th???a t??? Class Model s??? ph???i override l???i h??m n??y
        // ????? x??c ?????nh ch??nh x??c tableName t????ng ???ng
    }
    public static function getTableName()
    {
        return static::tableName();
    }
}
