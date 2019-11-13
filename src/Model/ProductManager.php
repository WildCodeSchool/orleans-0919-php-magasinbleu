<?php

namespace App\Model;

use App\Model\CategoryManager;
use App\Model\BrandManager;

class ProductManager extends AbstractManager
{
    const NB_LAST_PRODUCTS = 3;
    const TABLE = 'product';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function lastProduct(): array
    {
        $query = 'SELECT p.*, c.name AS category_name, b.name AS brand_name FROM ' . self::TABLE .
                    ' p JOIN ' . CategoryManager::TABLE . ' c ON p.category_id = c.id 
                        JOIN ' . BrandManager::TABLE . ' b ON p.brand_id = b.id 
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
                    FROM ' . self::TABLE . ' p 
                    JOIN ' . UniverseManager::TABLE . ' u ON p.universe_id = u.id 
                    JOIN ' . BrandManager::TABLE. ' b ON p.brand_id = b.id 
                    JOIN ' . CategoryManager::TABLE . ' c ON p.category_id = c.id 
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
        $query = 'SELECT COUNT(p.id) AS count FROM ' . self::TABLE . ' p 
                    JOIN ' . UniverseManager::TABLE . ' u ON p.universe_id = u.id 
                    JOIN ' . BrandManager::TABLE . ' b ON p.brand_id = b.id 
                    JOIN ' . CategoryManager::TABLE . ' c ON p.category_id = c.id 
                    WHERE u.name = :universe AND b.name LIKE :brand and c.name LIKE :category';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('universe', $filterPage['universe'], \PDO::PARAM_STR);
        $statement->bindValue('brand', $filterPage['brand'], \PDO::PARAM_STR);
        $statement->bindValue('category', $filterPage['category'], \PDO::PARAM_STR);
        $statement->execute();
        return (int)$statement->fetch()['count'];
    }
}
