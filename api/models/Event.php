<?php

namespace model;

use JsonSerializable;

require_once __DIR__ . '/BaseModel.php';

class Event extends BaseModel implements JsonSerializable
{

    public function jsonSerialize(): array
    {
        // TODO: Implement jsonSerialize() method.
    }
}