<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class BrandManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'brand';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function update(array $data)
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . "
                SET name=:name            
                WHERE id=:id
            ");
        $statement->bindValue('name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue('id', $data['id'], \PDO::PARAM_INT);
        $statement->execute();
    }
}
