<?php

namespace App\Model;

use App\Model\ProductManager;

class BrandManager extends AbstractManager
{
    const TABLE = 'brand';
    const TABLE_UNIVERSE = 'universe';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectFromUniverse(string $universe): array
    {
        $query = 'SELECT DISTINCT b.name AS brand_name FROM ' . ProductManager::TABLE . ' p 
                    JOIN ' . self::TABLE_UNIVERSE . ' u ON p.universe_id = u.id 
                    JOIN ' . $this->table . ' b ON p.brand_id = b.id 
                    WHERE u.name = :universe ORDER BY b.name ASC';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('universe', $universe, \PDO::PARAM_STR);
        $statement->execute();
        $brands = $statement->fetchAll();
        return $brands;
    }
}
