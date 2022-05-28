<?php

namespace App\Manager;

use App\Database\DBTransaction;
use App\Gateway\User as UserGateway;

class User
{
    const LIMIT = 10;

    /**
     * Возвращает пользователей старше заданного возраста.
     * @param int $ageFrom
     * @return array
     */
    public function getUsersByAgeFrom(int $ageFrom): array
    {
        $ageFrom = (int)trim($ageFrom);

        return UserGateway::getUserByAgeFrom($ageFrom, self::LIMIT);
    }

    /**
     * Возвращает пользователей по списку имен.
     * @param array $names
     * @return array
     */
    public function getUsersByNames(array $names): array
    {
        $users = [];
        foreach ($names as $name) {
            $users[] = UserGateway::getUserByName($name);
        }

        return $users;
    }

    /**
     * Добавляет пользователей в базу данных.
     * @param array $users
     * @return array
     */
    public function storeUsers(array $users): array
    {
        $ids = [];

        return DBTransaction::transaction(function ($db) use ($users, $ids) {
            foreach ($users as $user) {
                UserGateway::store($user['name'], $user['lastName'], $user['age']);
                $ids[] = $db->lastInsertId();
            }
            return $ids;
        });
    }
}