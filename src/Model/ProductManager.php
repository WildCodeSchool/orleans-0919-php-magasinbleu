<?php

namespace App\Model;

class ProductManager extends AbstractManager
{
    const TABLE = 'product';
    const TABLE_UNIVERSE = 'universe';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /* 3 last products Homepage */
    public function selectAll(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ORDER BY id DESC LIMIT 3 ')->fetchAll();
    }

    /**
     * Get all row from database where universe.
     *
     * @return array
     */

    public function selectUniverse(string $universe, int $page, int $productByPage): array
    {
        $query = 'SELECT p.*, u.name AS universe_name FROM ' . $this->table . ' p 
                    JOIN ' . self::TABLE_UNIVERSE . ' u ON p.universe_id = u.id 
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
