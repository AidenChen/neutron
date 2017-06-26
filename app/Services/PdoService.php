<?php

namespace App\Services;

use App\Exceptions\ApplicationException;

class PdoService
{
    private $pdo;

    private $sQuery;

    private $bConnected = false;

    private $parameters;

    public function __construct()
    {
        $this->Connect();
        $this->parameters = [];
    }

    private function Connect()
    {
        $settings = config('database.connections.mysql');
        $dsn = 'mysql:dbname=' . $settings['database'] . ';host=' . $settings['host'] . ';charset=utf8';
        try {
            $this->pdo = new \PDO($dsn, $settings['username'], $settings['password'], [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            ]);

            $this->bConnected = true;
        } catch (\PDOException $e) {
            $this->outputError($e->getMessage());
        }
    }

    public function CloseConnection()
    {
        $this->pdo = null;
    }

    private function outputError($strErrMsg, $query = '')
    {
        throw new ApplicationException(50000);
//        die(json_encode([
//            'success' => false,
//            'detail' => $strErrMsg,
//            'query' => $query
//        ]));
    }

    private function Init($query, $parameters = '')
    {
        if (! $this->bConnected) {
            $this->Connect();
        }
        try {
            $this->parameters = $parameters;
            $this->sQuery = $this->pdo->prepare($this->BuildParams($query, $this->parameters));

            if (! empty($this->parameters)) {
                if (array_key_exists(0, $parameters)) {
                    $parametersType = true;
                    array_unshift($this->parameters, '');
                    unset($this->parameters[0]);
                } else {
                    $parametersType = false;
                }
                foreach ($this->parameters as $column => $value) {
                    $this->sQuery->bindParam($parametersType ? intval($column) : ':' . $column, $this->parameters[$column]);
                }
            }

            $this->sQuery->execute();
        } catch (\PDOException $e) {
            $this->outputError($e->getMessage(), $this->BuildParams($query));
        }

        $this->parameters = [];
    }

    private function BuildParams($query, $params = null)
    {
        if (! empty($params)) {
            $rawStatement = explode(' ', $query);
            foreach ($rawStatement as $value) {
                if (strtolower($value) === 'in') {
                    return str_replace('(?)', '(' . implode(',', array_fill(0, count($params), '?')) . ')', $query);
                }
            }
        }
        return $query;
    }

    public function query($query, $params = null, $fetchmode = \PDO::FETCH_ASSOC)
    {
        $query = trim(str_replace('\r', ' ', $query));

        $this->Init($query, $params);

        $rawStatement = explode(' ', preg_replace('/\s+|\t+|\n+/', ' ', $query));

        $statement = strtolower($rawStatement[0]);

        if ($statement === 'select' || $statement === 'show') {
            return $this->sQuery->fetchAll($fetchmode);
        } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
            return $this->sQuery->rowCount();
        } else {
            return null;
        }
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function executeTransaction()
    {
        return $this->pdo->commit();
    }

    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    public function column($query, $params = null)
    {
        $this->Init($query, $params);
        $Columns = $this->sQuery->fetchAll(\PDO::FETCH_NUM);

        $column = null;

        foreach ($Columns as $cells) {
            $column[] = $cells[0];
        }

        return $column;
    }

    public function row($query, $params = null, $fetchmode = \PDO::FETCH_ASSOC)
    {
        $this->Init($query, $params);
        $result = $this->sQuery->fetch($fetchmode);
        $this->sQuery->closeCursor();
        return $result;
    }

    public function single($query, $params = null)
    {
        $this->Init($query, $params);
        $result = $this->sQuery->fetchColumn();
        $this->sQuery->closeCursor();
        return $result;
    }
}
