<?php

namespace App\Model;

class BrandManager extends AbstractManager
{
    const TABLE = 'brand';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAll(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ORDER BY name ASC')->fetchAll();
    }
}
