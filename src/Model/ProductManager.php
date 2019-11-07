<?php

namespace App\Model;

class ProductManager extends AbstractManager
{
    const TABLE = 'product';
    const TABLE_UNIVERSE = 'universe';
    const TABLE_CATEGORY = 'category';
    const TABLE_BRAND = 'brand';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function lastProduct(): array
    {
        $query = 'SELECT p.*, c.name AS category_name, b.name AS brand_name FROM ' . $this->table .
                    ' p JOIN ' . self::TABLE_CATEGORY . ' c ON p.category_id = c.id 
                        JOIN ' . self::TABLE_BRAND . ' b ON p.brand_id = b.id 
                        ORDER BY p.id DESC LIMIT 3 ';
        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * Get all row from database where universe.
     *
     * @return array
     */

    public function selectUniverse(string $universe, int $page, int $productByPage): array
    {
        $query = 'SELECT p.*, u.name AS universe_name, b.name AS brand_name, c.name AS category_name 
                    FROM ' . $this->table . ' p 
                    JOIN ' . self::TABLE_UNIVERSE . ' u ON p.universe_id = u.id 
                    JOIN ' . self::TABLE_BRAND . ' b ON p.brand_id = b.id 
                    JOIN ' . self::TABLE_CATEGORY . ' c ON p.category_id = c.id 
                    WHERE u.name = :universe LIMIT ' . $productByPage . ' OFFSET ' . $productByPage*($page-1);
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('universe', $universe, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function countProducts(string $universe): int
    {
        $query = 'SELECT COUNT(p.id) AS count FROM ' . $this->table . ' p 
                    JOIN ' . self::TABLE_UNIVERSE . ' u ON p.universe_id = u.id 
                    WHERE u.name = :universe';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('universe', $universe, \PDO::PARAM_STR);
        $statement->execute();
        return (int)$statement->fetch()['count'];
    }
}
