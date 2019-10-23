<?php

namespace App\Model;

class HomeManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'universe';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
