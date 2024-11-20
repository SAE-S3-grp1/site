<?php

namespace model;

require_once "../DB.php";

class BaseModel
{

    protected int $id {
        get {
            return $this->id;
        }
    }
    protected \DB $DB;

    protected function __construct($id)
    {
        $this->id = $id;
        $this->DB = new \DB();
    }

}