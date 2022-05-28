<?php

namespace App\Database;

use Closure;
use Exception;

class DBTransaction
{
    /**
     * @param Closure $callback
     * @return array
     */
    public static function transaction(Closure $callback): array
    {
        $db = DBConnection::getInstance();

        $result = [];

        $db->beginTransaction();
        try {
            $result = $callback($db);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
        }

        return $result;
    }
}