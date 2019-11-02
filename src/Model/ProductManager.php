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

    /**
     * Get all row from database where universe.
     *
     * @return array
     */
    public function selectUniverse(string $universe): array
    {
        $query = 'SELECT p.*, u.name AS universe_name FROM ' . $this->table . ' p 
                    JOIN ' . self::TABLE_UNIVERSE . ' u ON p.universe_id = u.id 
                    WHERE u.name = :universe';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('universe', $universe, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }
}
