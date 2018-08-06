<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 31/07/2018
 * Time: 11:52
 */

namespace App\Repositories;


use App\Models\Application;
use App\Models\DatabaseConnection;
use \PDOException;
class ApplicationRepository
{
private $app;

private  $dbLocalConnection;
    /**
     * ApplicationRepository constructor.
     * @param $app
     */
    public function __construct(Application $app=null)
    {


        $this->app = $app;
    }
    public function testConnection(){

        return $this->app->getConnection();
    }

    public function add(){
       try{
                                                                                                                                //$host, $username, $password, $database, $type, $port
           //:name, :appname, :login,:password,:ip,:query,:username,:lastAuth,:lastUpdate,:status,:matricule
           //name, appname, login,password,ip,query,username,lastAuth,lastUpdate,status,matricule
           //execute(array(":name"=>$this->app->getName(),":appname"=>$this->app->getDatabase(),"login"=>$this->app->getLogin(),"password",$this->app->getPassword(),"ip"));
           $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
           $this->dbLocalConnection=$db->getConnection();

           $result = $this->dbLocalConnection->prepare("INSERT INTO applications (name, dbname, login,password,ip,query,username,lastAuth,lastUpdate,status,matricule) values (?, ?, ?,?,?,?,?,?,?,?,?)");
           $result->execute(array($this->app->getName(),$this->app->getDatabase(),$this->app->getLogin(),$this->app->getPassword(),$this->app->getHost(),
               $this->app->getQuery(),$this->app->getUsername(),$this->app->getLastAuth(),$this->app->getLastUpdate(),$this->app->getStatus(),
               $this->app->getMatricule()));

          $this->dbLocalConnection=null;
           return true;
       }catch (PDOException $ex){
return false;
       }

    }
    public function getApplications(){
        $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
        $this->dbLocalConnection=$db->getConnection();
        $result= $this->dbLocalConnection->prepare("select * from applications");
        $result->execute();
        $this->dbLocalConnection=null;
        return $result;

    }
    public function getUsersOfApplicationById($name){
        $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
        $this->dbLocalConnection=$db->getConnection();

        //get users of application by name
        $result=$this->dbLocalConnection->prepare("select * from users_".$name);
        $result->execute();
        $this->dbLocalConnection=null;
        return $result;
    }

    public  function  closeConnection(){
        $this->app->closeConnection();
    }

public  function  getApplicationByName($name){
    $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
    $this->dbLocalConnection=$db->getConnection();

    //get users of application by name
    $result=$this->dbLocalConnection->prepare("select * from applications where  name = ?");
    $result->execute(array($name));
    $this->dbLocalConnection=null;
    return $result->fetch();
}
public function  createTableUsers(){
        /*

     derniere_auth  datetime,
     derniere_auth datetime,
     matricule VARCHAR( 255 ) ,
     status tinyint(1)  );
         */
        try{


    $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
    $this->dbLocalConnection=$db->getConnection();
    $table="users_".$this->app->getName();
    $sql=" CREATE TABLE $table (
     ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
     username VARCHAR( 255 ) NOT NULL,";
    $fields=array();
if(!empty($this->app->getLastUpdate())){
array_push($fields,"lastUpdate  datetime");
}
    if(!empty($this->app->getLastAuth())){
        array_push($fields,"lastAuth  datetime");
    }
    if(!empty($this->app->getMatricule())){
        array_push($fields,"matricule  VARCHAR( 255 )");
    }
    if(!empty($this->app->getMatricule())){
        array_push($fields,"status tinyint(1) ");
    }
    $sql=$sql . implode(",", $fields).",authenticUsername VARCHAR( 255 ))";


$this->dbLocalConnection->exec($sql);
$this->dbLocalConnection=null;
        }catch (PDOException $ex){
            return false;
        }

}

    public function  fillTableUsers(){
        try{
            $remoteConnection=$this->app->getConnection();
            $remoteConnection->query($this->app->getQuery());

            foreach ($remoteConnection->query($this->app->getQuery()) as $row){
                $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
                $this->dbLocalConnection=$db->getConnection();
                $table="users_".$this->app->getName();

                $fields=array('username');
                $values=array('?');
                $data=array($row[$this->app->getUsername()]);
                if(!empty($this->app->getLastUpdate())){
                    array_push($fields,"lastUpdate");
                    array_push($values,"?");
                    array_push($data,$row[$this->app->getLastUpdate()]);

                }
                if(!empty($this->app->getLastAuth())){
                    array_push($fields,"lastAuth");
                    array_push($values,"?");
                    array_push($data,$row[$this->app->getLastAuth()]);
                }
                if(!empty($this->app->getMatricule())){
                    array_push($fields,"matricule");
                    array_push($values,"?");
                    array_push($data,$row[$this->app->getMatricule()]);
                }
                if(!empty($this->app->getStatus())){
                    array_push($fields,"status");
                    array_push($values,"?");
                    array_push($data,$row[$this->app->getStatus()]);
                }
                //build query insert users
                $sql="INSERT INTO $table (".implode(",", $fields).") values (".implode(",", $values).")";
              $result=  $this->dbLocalConnection->prepare($sql);
                $result->execute($data);
            }



        }catch (PDOException $ex){
            return false;
        }
    }
}