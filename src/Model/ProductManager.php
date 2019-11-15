<?php

namespace App\Model;

use App\Model\CategoryManager;
use App\Model\BrandManager;

class ProductManager extends AbstractManager
{
    const NB_LAST_PRODUCTS = 5;
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
        $queryJoin = 'SELECT p.*, u.name AS universe_name, b.name AS brand_name, c.name AS category_name 
                    FROM ' . self::TABLE . ' p 
                    JOIN ' . UniverseManager::TABLE . ' u ON p.universe_id = u.id 
                    JOIN ' . BrandManager::TABLE. ' b ON p.brand_id = b.id 
                    JOIN ' . CategoryManager::TABLE . ' c ON p.category_id = c.id';
        $queryOrder = 'ORDER BY p.id ASC LIMIT ' . $productByPage . ' OFFSET ' . $productByPage*($page-1);
        if (isset($filterPage['available'])) {
            $queryFilter =
                'WHERE u.name = :universe AND b.name LIKE :brand AND c.name LIKE :category AND availability ';
        } else {
            $queryFilter = 'WHERE u.name = :universe AND b.name LIKE :brand AND c.name LIKE :category';
        }

        $statement = $this->pdo->prepare($queryJoin . ' ' . $queryFilter . ' ' . $queryOrder);
        $statement->bindValue('universe', $filterPage['universe'], \PDO::PARAM_STR);
        $statement->bindValue('brand', $filterPage['brand'] ?? '%', \PDO::PARAM_STR);
        $statement->bindValue('category', $filterPage['category'] ?? '%', \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function countProducts(array $filterPage): int
    {
        $queryJoin = 'SELECT COUNT(p.id) AS count FROM ' . self::TABLE . ' p 
                    JOIN ' . UniverseManager::TABLE . ' u ON p.universe_id = u.id 
                    JOIN ' . BrandManager::TABLE . ' b ON p.brand_id = b.id 
                    JOIN ' . CategoryManager::TABLE . ' c ON p.category_id = c.id';
        if (isset($filterPage['available'])) {
            $queryFilter =
                'WHERE u.name = :universe AND b.name LIKE :brand AND c.name LIKE :category AND availability ';
        } else {
            $queryFilter = 'WHERE u.name = :universe AND b.name LIKE :brand AND c.name LIKE :category';
        }

        $statement = $this->pdo->prepare($queryJoin . ' ' . $queryFilter);
        $statement->bindValue('universe', $filterPage['universe'], \PDO::PARAM_STR);
        $statement->bindValue('brand', $filterPage['brand'] ?? '%', \PDO::PARAM_STR);
        $statement->bindValue('category', $filterPage['category'] ?? '%', \PDO::PARAM_STR);
        $statement->execute();
        return (int)$statement->fetch()['count'];
    }


    public function selectOneById(int $id)
    {
        $query = 'SELECT p.*, b.name AS brand_name, c.name AS category_name 
                    FROM ' . self::TABLE . ' p 
                    JOIN ' . CategoryManager::TABLE. ' c ON p.category_id = c.id 
                    JOIN ' . BrandManager::TABLE . ' b ON p.brand_id = b.id 
                  WHERE p.id=:id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

    public function update(array $data)
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE  . "
                SET name=:name, image=:image, reference=:reference, price=:price, description=:description,
                availability=:availability, universe_id=:universe, brand_id=:brand, category_id=:category            
                WHERE id=:id
            ");
        $statement->bindValue('name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue('image', $data['image'], \PDO::PARAM_STR);
        $statement->bindValue('reference', $data['reference'], \PDO::PARAM_STR);
        $statement->bindValue('price', $data['price'], \PDO::PARAM_INT);
        $statement->bindValue('description', $data['description'], \PDO::PARAM_STR);
        $statement->bindValue('availability', $data['availability'], \PDO::PARAM_BOOL);
        $statement->bindValue('universe', $data['universe'], \PDO::PARAM_INT);
        $statement->bindValue('brand', $data['brand'], \PDO::PARAM_INT);
        $statement->bindValue('category', $data['category'], \PDO::PARAM_INT);
        $statement->bindValue('id', $data['id'], \PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(int $id)
    {
        $query = 'DELETE from ' . self::TABLE . ' WHERE id=:id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
    }

    public function insert(array $data)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "
               (name, image, reference, price, description, availability, universe_id, brand_id, category_id)
               VALUES (:name, :image, :reference, :price, :description, :availability, :universe, :brand, :category)
           ");
        $statement->bindValue('name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue('image', $data['image'], \PDO::PARAM_STR);
        $statement->bindValue('reference', $data['reference'], \PDO::PARAM_STR);
        $statement->bindValue('price', $data['price'], \PDO::PARAM_INT);
        $statement->bindValue('description', $data['description'], \PDO::PARAM_STR);
        $statement->bindValue('availability', $data['availability'], \PDO::PARAM_BOOL);
        $statement->bindValue('universe', $data['universe'], \PDO::PARAM_INT);
        $statement->bindValue('brand', $data['brand'], \PDO::PARAM_INT);
        $statement->bindValue('category', $data['category'], \PDO::PARAM_INT);
        $statement->execute();
    }
}
