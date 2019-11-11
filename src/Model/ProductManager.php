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
    public function selectUniverse(array $filterPage, int $page, int $productByPage): array
    {
        $query = 'SELECT p.*, u.name AS universe_name, b.name AS brand_name, c.name AS category_name 
                    FROM ' . $this->table . ' p 
                    JOIN ' . self::TABLE_UNIVERSE . ' u ON p.universe_id = u.id 
                    JOIN ' . self::TABLE_BRAND . ' b ON p.brand_id = b.id 
                    JOIN ' . self::TABLE_CATEGORY . ' c ON p.category_id = c.id 
                    WHERE u.name = :universe AND b.name LIKE :brand and c.name LIKE :category
                    LIMIT ' . $productByPage . ' OFFSET ' . $productByPage*($page-1);
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('universe', $filterPage['universe'], \PDO::PARAM_STR);
        $statement->bindValue('brand', $filterPage['brand'], \PDO::PARAM_STR);
        $statement->bindValue('category', $filterPage['category'], \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function countProducts(array $filterPage): int
    {
        $query = 'SELECT COUNT(p.id) AS count FROM ' . $this->table . ' p 
                    JOIN ' . self::TABLE_UNIVERSE . ' u ON p.universe_id = u.id 
                    JOIN ' . self::TABLE_BRAND . ' b ON p.brand_id = b.id 
                    JOIN ' . self::TABLE_CATEGORY . ' c ON p.category_id = c.id 
                    WHERE u.name = :universe AND b.name LIKE :brand and c.name LIKE :category';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('universe', $filterPage['universe'], \PDO::PARAM_STR);
        $statement->bindValue('brand', $filterPage['brand'], \PDO::PARAM_STR);
        $statement->bindValue('category', $filterPage['category'], \PDO::PARAM_STR);
        $statement->execute();
        return (int)$statement->fetch()['count'];
    }
}
