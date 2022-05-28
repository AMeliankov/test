<?php

namespace App\Gateway;

use PDO;
use App\Database\DBConnection;

class User
{
    /**
     * Возвращает список пользователей старше заданного возраста.
     * @param int $ageFrom
     * @param int $limit
     * @return array
     */
    public static function getUserByAgeFrom(int $ageFrom,  int $limit): array
    {
        $db = DBConnection::getInstance();

        $sql = "SELECT id, name, lastName, from, age, settings FROM users WHERE age > :ageFrom LIMIT :limit";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'ageFrom' => $ageFrom,
            'limit' => $limit,
        ]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($rows as $row) {
            $settings = json_decode($row['settings']);
            $users[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'lastName' => $row['lastName'],
                'from' => $row['from'],
                'age' => $row['age'],
                'key' => $settings['key'],
            ];
        }

        return $users;
    }

    /**
     * Возвращает пользователя по имени.
     * @param string $name
     * @return array
     */
    public static function getUserByName(string $name): array
    {
        $db = DBConnection::getInstance();

        $sql = "SELECT id, name, lastName, from, age FROM users WHERE name = :name";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'name' => $name,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Добавляет пользователя в базу данных.
     * @param string $name
     * @param string $lastName
     * @param int $age
     * @return string
     */
    public static function store(string $name, string $lastName, int $age): string
    {
        $db = DBConnection::getInstance();

        $sql = "INSERT INTO users (name, lastName, age) VALUES (:name, :lastName, :age)";
        $sth = $db->prepare($sql);
        $sth->execute([
            'name' => $name,
            'lastName' => $lastName,
            'age' => $age,
        ]);

        return DBConnection::getInstance()->lastInsertId();
    }
}