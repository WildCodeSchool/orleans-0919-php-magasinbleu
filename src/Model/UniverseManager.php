<?php

namespace App\Model;

class UniverseManager extends AbstractManager
{

    const TABLE = 'universe';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
