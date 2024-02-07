<?php

namespace Poo\Project\Core\Orm;

use PDO;
use Poo\Project\Config\Config;

class Model extends PDO
{
    private static $instance = null;

    private function __construct()
    {
        try {
            parent::__construct(
                "mysql:dbname=" . Config::DBNAME . ";host=" . Config::DBHOST,
                Config::DBUSER,
                Config::DBPWD,
            );
        } catch (\PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
//            header('Location: ./');
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Model;
        }
        return self::$instance;
    }

    /**
     * retourne un tableau d'entity, attention: peux retourner un tableau vide, penser a verifier avec la methode PHP empty($array)
     */
    public function readAll(string $entity)
    {
        $query = $this->query("SELECT * FROM " . $entity);
        return $query->fetchAll(PDO::FETCH_CLASS, 'Poo\Project\Entity\\' . ucfirst($entity));
    }

    public function getByAttributeLike(string $entity, string $attribute, $value)
    {
        $query = $this->query("SELECT * FROM " . $entity . " WHERE " . $attribute . " LIKE '%" . $value . "%'");
        return $query->fetchAll(PDO::FETCH_CLASS, 'Poo\Project\Entity\\' . ucfirst($entity));
    }

    /**
     * retourne une entity, si la requete ne trouve rien, retourne un booleen false.
     * pensez donc a utiliser une conditionnel type: if($result){ ... }
     */
    public function getById(string $entity, int $id)
    {
        $query = $this->query("SELECT * FROM " . $entity . " WHERE id=" . $id);
        $result = $query->fetchAll(PDO::FETCH_CLASS, 'Poo\Project\Entity\\' . ucfirst($entity));
        if (empty($result)) {
            return false;
        }
        return $result[0];
    }

    public function findOneBy(string $entity, string $attribute, $value)
    {
        $query = $this->query("SELECT * FROM " . $entity . " WHERE " . $attribute . "='" . $value . "'");
        $result = $query->fetchAll(PDO::FETCH_CLASS, 'Poo\Project\Entity\\' . ucfirst($entity));
        if (empty($result)) {
            return false;
        }
        return $result[0];
    }
    /**
     * retourne un tableau d'entity, 
     * le parametre $attributes se presente sous la forme [ 'nom_de_colonne' => 'valeur'],
     * il peux prendre en compte plusieurs attributs
     * @param array<string,mixed> $attributes
     */
    public function getByAttribute(string $entity, array $attributes)
    {
        $count = count($attributes);
        $i = 1;
        $sql = "SELECT * FROM " . $entity . " WHERE ";
        foreach ($attributes as $attribute => $value) {
            $sql .= $attribute . " = " . $value;
            if ($i < $count) {
                $sql .= " AND ";
            }
            $i++;
        }
        $query = $this->query($sql);
        return $query->fetchAll(PDO::FETCH_CLASS, 'Poo\Project\Entity\\' . ucfirst($entity));
    }

    public function save(string $entity, array $datas)
    {
        $sql = 'INSERT into ' . $entity . ' (';
        $count = count($datas);
        $preparedDatas = [];
        $i = 1;
        foreach ($datas as $key => $value) {
            $preparedDatas[] = htmlspecialchars($value);
            $sql .= $key;
            if ($i < $count) {
                $sql .= ', ';
            }
            $i++;
        }
        $sql .= ') VALUES (';
        $i = 1;
        foreach ($datas as $value) {
            $sql .= '?';
            if ($i < $count) {
                $sql .= ', ';
            }
            $i++;
        }
        $sql .= ')';
        $preparedRequest = $this->prepare($sql);
        $preparedRequest->execute($preparedDatas);
    }

    public function updateById(string $entity, int $id, array $datas)
    {
        $sql = 'UPDATE ' . $entity . ' SET ';
        $count = count($datas) - 1;
        $preparedDatas = [];
        $i = 0;
        foreach ($datas as $key => $value) {
            $preparedDatas[] = htmlspecialchars($value);
            $sql .= $key . " = ?";
            if ($i < $count) {
                $sql = $sql . ', ';
            }
            $i++;
        }
        $sql = $sql . " WHERE id='$id'";
        $preparedRequest = $this->prepare($sql);
        $preparedRequest->execute($preparedDatas);
    }

    public function deleteById(string $entity, int $id)
    {
        $sql = "DELETE from $entity WHERE id = '$id'";
        $this->exec($sql);
    }
}
