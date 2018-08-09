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


    public function __construct( $name, $dbName, $host, $login, $password, $type, $port, $query)
    {

        //( $host, $username, $password, $database, $type, $port)
        parent::__construct($host,$login,$password,$dbName,$type,$port);

        $this->name = $name;
        $this->query = $query;

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





}