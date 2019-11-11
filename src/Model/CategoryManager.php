<?php

namespace App\Model;

class CategoryManager extends AbstractManager
{
    const TABLE = 'category';
    const TABLE_PRODUCT = 'product';
    const TABLE_UNIVERSE = 'universe';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAll(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ORDER BY name ASC')->fetchAll();
    }

    public function selectFromUniverse(string $universe): array
    {
        $query = 'SELECT DISTINCT c.name AS category_name FROM ' . self::TABLE_PRODUCT . ' p 
                    JOIN ' . self::TABLE_UNIVERSE . ' u ON p.universe_id = u.id 
                    JOIN ' . $this->table . ' c ON p.category_id = c.id 
                    WHERE u.name = :universe ORDER BY c.name ASC';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('universe', $universe, \PDO::PARAM_STR);
        $statement->execute();
        $categories = $statement->fetchAll();
        return $categories;
    }
}
