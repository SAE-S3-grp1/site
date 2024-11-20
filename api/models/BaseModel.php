<?php

namespace model;

class BaseModel
{

    protected $id;
    protected  $DB;

    protected function __construct($id)
    {
        $this->id = $id;
        $this->DB = new \DB();
    }

    public function getId()
    {
        return $this->id;
    }

}