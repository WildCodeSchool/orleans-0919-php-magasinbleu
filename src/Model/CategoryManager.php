<?php

namespace App\Model;

class CategoryManager extends AbstractManager
{
    const TABLE = 'category';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAll(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ORDER BY name ASC')->fetchAll();
    }
}
