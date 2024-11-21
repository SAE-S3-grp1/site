<?php

namespace model;



require_once __DIR__ . '/File.php';
require_once __DIR__ . '/Role.php';
require_once __DIR__ . '/Member.php';
require_once __DIR__ . '/Grade.php';
require_once __DIR__ . '/Item.php';


class BaseModel
{

    protected int $id;
    protected \DB $DB;


    public function getId(): int
    {
        return $this->id;
    }

    protected function __construct($id)
    {
        $this->id = $id;
        $this->DB = new \DB();
    }

}