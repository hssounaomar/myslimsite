<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 31/07/2018
 * Time: 11:23
 */

namespace App\Models;
use \PDO;
use \PDOException;

class DatabaseConnection
{

    private  $connection;
    private $host;
    private $login ;
    private $password ;
    private $database ;
    private  $type;
    private  $port;

    /**
     * DatabaseConnection constructor.
     * @param $_connection
     * @param $host
     * @param $username
     * @param $password
     * @param $database
     * @param $type
     * @param $port
     */
    public function __construct( $host, $username, $password, $database, $type, $port)
    {

        $this->host = $host;
        $this->login = $username;
        $this->password = $password;
        $this->database = $database;
        $this->type = $type;
        $this->port = $port;

    }
    /*
    Get an instance of the Database
    @return Instance
    */


    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }
    // Get mysqli connection
    public function getConnection() {
        try{
            if($this->type=="oci"){

                $this->connection = new PDO('oci:dbname='.$this->host.'/'.$this->database."; charset=utf8", $this->login, $this->password);

            }else{
                $this->connection = new PDO('mysql:host='.$this->host.';dbname='.$this->database.';port='.$this->port."; charset=utf8",$this->login,$this->password);
            }


            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
return $this->connection;




        }catch(PDOException $ex){

            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param mixed $database
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }
    public function closeConnection(){
        $this->connection =null;
    }

}