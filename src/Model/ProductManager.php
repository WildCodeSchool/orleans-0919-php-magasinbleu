<?php

namespace App\Model;

class ProductManager extends AbstractManager
{
    const NB_LAST_PRODUCTS = 3;
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
                        ORDER BY p.id DESC LIMIT ' . self::NB_LAST_PRODUCTS;
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

    public function describe(array $products): int
    {
        $query = 'SELECT p.name, p.description, b.name, c.name, p.reference, p.price, p.image
                  FROM ' . $this->table . ' p
                  JOIN ' . self::TABLE_CATEGORY . ' c ON p.category_id = c.id
                  JOIN ' . self::TABLE_BRAND . ' b ON p.brand_id = b.id ';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('products', $products, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }
}
