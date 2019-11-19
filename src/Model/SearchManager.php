<?php

namespace App\Model;

class SearchManager extends AbstractManager
{
    const TABLE = 'product';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function countSearchedProducts(string $searchTerm, array $filterPage): int
    {
        $queryJoin = 'SELECT COUNT(p.id) AS count FROM ' . self::TABLE . ' p 
                    JOIN ' . UniverseManager::TABLE . ' u ON p.universe_id = u.id 
                    JOIN ' . BrandManager::TABLE . ' b ON p.brand_id = b.id 
                    JOIN ' . CategoryManager::TABLE . ' c ON p.category_id = c.id';
        $queryFilter = 'WHERE u.name LIKE :universe AND b.name LIKE :brand AND c.name LIKE :category';
        if (isset($filterPage['available'])) {
            $queryFilter .= ' AND availability ';
        }
        $querySearch = $this->searchQueryGeneration($searchTerm);
        $query = $queryJoin . ' ' . $queryFilter . ' AND (' . $querySearch . ')';

        $statement = $this->pdo->prepare($query);
        $statement->bindValue('universe', $filterPage['universe'] ?? '%', \PDO::PARAM_STR);
        $statement->bindValue('brand', $filterPage['brand'] ?? '%', \PDO::PARAM_STR);
        $statement->bindValue('category', $filterPage['category'] ?? '%', \PDO::PARAM_STR);
        if (strlen($searchTerm) > 0) {
            if (($searchTerm[0] == '\'' && substr($searchTerm, -1) == '\'')
                || ($searchTerm[0] == '"' && substr($searchTerm, -1) == '"')) {
                $statement->bindValue('search', substr($searchTerm, 1, strlen($searchTerm)-2), \PDO::PARAM_STR);
            } else {
                $terms = explode(' ', $searchTerm);
                $numberTerms = count($terms);
                foreach ($terms as $term) {
                    $statement->bindValue('search' . $numberTerms, '%' . $term . '%', \PDO::PARAM_STR);
                    $numberTerms--;
                }
            }
        } else {
            $statement->bindValue('search', '%', \PDO::PARAM_STR);
        }
        $statement->execute();
        return (int)$statement->fetch()['count'];
    }

    public function searchProducts(array $filterPage, string $searchTerm, int $page, int $productByPage): array
    {
        $queryJoin = 'SELECT p.*, u.name AS universe_name, b.name AS brand_name, c.name AS category_name 
                    FROM ' . self::TABLE . ' p 
                    JOIN ' . UniverseManager::TABLE . ' u ON p.universe_id = u.id 
                    JOIN ' . BrandManager::TABLE. ' b ON p.brand_id = b.id 
                    JOIN ' . CategoryManager::TABLE . ' c ON p.category_id = c.id';
        $queryOrder = 'ORDER BY p.name ASC LIMIT ' . $productByPage . ' OFFSET ' . $productByPage*($page-1);
        $queryFilter = 'WHERE u.name LIKE :universe AND b.name LIKE :brand AND c.name LIKE :category';
        if (isset($filterPage['available'])) {
            $queryFilter .= ' AND availability ';
        }
        $querySearch = $this->searchQueryGeneration($searchTerm);
        $query = $queryJoin . ' ' . $queryFilter . ' AND (' . $querySearch . ') ' . $queryOrder;

        $statement = $this->pdo->prepare($query);
        $statement->bindValue('universe', $filterPage['universe'] ?? '%', \PDO::PARAM_STR);
        $statement->bindValue('brand', $filterPage['brand'] ?? '%', \PDO::PARAM_STR);
        $statement->bindValue('category', $filterPage['category'] ?? '%', \PDO::PARAM_STR);
        if (strlen($searchTerm) > 0) {
            if (($searchTerm[0] == '\'' && substr($searchTerm, -1) == '\'')
                || ($searchTerm[0] == '"' && substr($searchTerm, -1) == '"')) {
                $statement->bindValue('search', substr($searchTerm, 1, strlen($searchTerm)-2), \PDO::PARAM_STR);
            } else {
                $terms = explode(' ', $searchTerm);
                $numberTerms = count($terms);
                foreach ($terms as $term) {
                    $statement->bindValue('search' . $numberTerms, '%' . $term . '%', \PDO::PARAM_STR);
                    $numberTerms--;
                }
            }
        } else {
            $statement->bindValue('search', '%', \PDO::PARAM_STR);
        }
        $statement->execute();
        return $statement->fetchAll();
    }

    private function searchQueryGeneration(string $searchTerm): string
    {
        $querySearch = ' ';
        if (strlen($searchTerm) >0
            && ($searchTerm[0] != '\'' && substr($searchTerm, -1) != '\'')
            && ($searchTerm[0] != '"' && substr($searchTerm, -1) != '"')) {
            $terms = explode(' ', $searchTerm);
            $numberTerms = count($terms);
            while ($numberTerms > 1) {
                $querySearch .=
                    '(p.name LIKE :search' . $numberTerms .  ' OR p.reference LIKE :search' . $numberTerms . ') OR ';
                $numberTerms--;
            }
            $querySearch .=
                '(p.name LIKE :search' . $numberTerms .  ' OR p.reference LIKE :search' . $numberTerms . ')';
        } else {
            $querySearch .= '(p.name LIKE :search OR p.reference LIKE :search)';
        }
        return $querySearch;
    }

    public function searchAdminProducts(string $searchTerm): array
    {
        $queryJoin = 'SELECT p.*, u.name AS universe_name, b.name AS brand_name, c.name AS category_name 
                    FROM ' . self::TABLE . ' p 
                    JOIN ' . UniverseManager::TABLE . ' u ON p.universe_id = u.id 
                    JOIN ' . BrandManager::TABLE. ' b ON p.brand_id = b.id 
                    JOIN ' . CategoryManager::TABLE . ' c ON p.category_id = c.id';
        $queryOrder = 'ORDER BY p.id DESC';

        $querySearch = $this->searchQueryGeneration($searchTerm);
        $query = $queryJoin . ' WHERE' . $querySearch . ' ' . $queryOrder;

        $statement = $this->pdo->prepare($query);
        if (strlen($searchTerm) > 0) {
            if (($searchTerm[0] == '\'' && substr($searchTerm, -1) == '\'')
                || ($searchTerm[0] == '"' && substr($searchTerm, -1) == '"')) {
                $statement->bindValue('search', substr($searchTerm, 1, strlen($searchTerm)-2), \PDO::PARAM_STR);
            } else {
                $terms = explode(' ', $searchTerm);
                $numberTerms = count($terms);
                foreach ($terms as $term) {
                    $statement->bindValue('search' . $numberTerms, '%' . $term . '%', \PDO::PARAM_STR);
                    $numberTerms--;
                }
            }
        } else {
            $statement->bindValue('search', '%', \PDO::PARAM_STR);
        }
        $statement->execute();
        return $statement->fetchAll();
    }
}
