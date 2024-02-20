<?php

namespace Mvc\Framework\Kernel;

use Mvc\Framework\Kernel\Model\Model;

class AbstractRepository
{
    private string $entity;

    public function __construct()
    {
        $arrayDir = explode("\\", get_class($this));
        $repositoryName = end($arrayDir);
        $this->entity = strtolower(substr($repositoryName, 0, strpos($repositoryName, 'Repository')));
    }

    public final function save(mixed $entity) : bool
    {
        $class = new \ReflectionClass(get_class($entity));
        $properties = $class->getProperties();
        $table = strtolower($class->getShortName());
        $columns = [];
        $values = [];
        foreach ($properties as $property) {
            if ($property->getName() !== 'id') {
                $values[] = $property->getValue($entity);
                $columns[] = $property->getName();
            }
        }
        $columns = implode(',', $columns);
        $values = implode("','", $values);
        $sql = "INSERT INTO $table ($columns) VALUES ('$values')";
        return (bool) Model::getInstance()->query($sql);
    }

    public final function findAll(): array
    {
        $sql = "SELECT * FROM $this->entity";
        return Model::getInstance()->query($sql);
    }

    public final function find(int $id): array
    {
        $sql = "SELECT * FROM $this->entity WHERE id = :id";
        return Model::getInstance()->query($sql, [':id' => $id]);
    }

    public final function delete(int $id): void
    {
        $sql = "DELETE FROM $this->entity WHERE id = :id";
        Model::getInstance()->query($sql, [':id' => $id]);
    }

    public final function update(int $id, array $data): void
    {
        $sql = "UPDATE $this->entity SET ";
        foreach ($data as $key => $value) {
            $sql .= "$key = :$key, ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= " WHERE id = :id";
        $data['id'] = $id;
        Model::getInstance()->query($sql, $data);
    }

    public final function insert(array $data): void
    {
        $sql = "INSERT INTO $this->entity (";
        foreach ($data as $key => $value) {
            $sql .= "$key, ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= ") VALUES (";
        foreach ($data as $key => $value) {
            $sql .= ":$key, ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= ")";
        Model::getInstance()->query($sql, $data);
    }

    public final function customQuery(string $sql, array $params = []): array
    {
        return Model::getInstance()->query($sql, $params);
    }

}