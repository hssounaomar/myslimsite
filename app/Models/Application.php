<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 30/07/2018
 * Time: 20:00
 */

namespace App\Models;


class Application extends  DatabaseConnection
{

    protected $id;
    protected $name;




protected $query;
//fields of table user
protected $username;
protected $lastAuth;
protected $status;
protected $matricule;
protected $lastUpdate;

    /**
     * Application constructor.
     * @param $id
     * @param $name
     * @param $dbName
     * @param $ip
     * @param $login
     * @param $password
     * @param $type
     * @param $port
     * @param $query
     * @param $username
     * @param $lastAuth
     * @param $status
     * @param $matricule
     * @param $lastModif
     */
    public function __construct( $name, $dbName, $host, $login, $password, $type, $port, $query, $username, $lastAuth, $status, $matricule, $lastModif)
    {
        //( $host, $username, $password, $database, $type, $port)
        parent::__construct($host,$login,$password,$dbName,$type,$port);

        $this->name = $name;
        $this->query = $query;
        $this->username = $username;
        $this->lastAuth = $lastAuth;
        $this->status = $status;
        $this->matricule = $matricule;
        $this->lastUpdate = $lastModif;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getLastAuth()
    {
        return $this->lastAuth;
    }

    /**
     * @param mixed $lastAuth
     */
    public function setLastAuth($lastAuth)
    {
        $this->lastAuth = $lastAuth;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * @param mixed $matricule
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @param mixed $lastUpdate
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
    }




}