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
//add application
    public function add(){
       try{
                                                                                                                                //$host, $username, $password, $database, $type, $port
           //:name, :appname, :login,:password,:ip,:query,:username,:lastAuth,:lastUpdate,:status,:matricule
           //name, appname, login,password,ip,query,username,lastAuth,lastUpdate,status,matricule
           //execute(array(":name"=>$this->app->getName(),":appname"=>$this->app->getDatabase(),"login"=>$this->app->getLogin(),"password",$this->app->getPassword(),"ip"));
           $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
           $this->dbLocalConnection=$db->getConnection();
$db=null;
           $result = $this->dbLocalConnection->prepare("INSERT INTO applications (name, dbname, login,password,ip,query,email,type,port) values (?, ?, ?,?,?,?,?,?,?)");
           $result->execute(array($this->app->getName(),$this->app->getDatabase(),$this->app->getLogin(),
               $this->app->getPassword(),$this->app->getHost(), $this->app->getQuery(),$this->app->getEmail(),
               $this->app->getType(),$this->app->getPort()));
$id=$this->dbLocalConnection->lastInsertId();
          $this->dbLocalConnection=null;
           return $id;
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
        return $result->fetchAll();

    }
    public function getUsersOfApplicationByName($name){
        $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
        $this->dbLocalConnection=$db->getConnection();

        //get users of application by name
        $result=$this->dbLocalConnection->prepare("select * from users_".$name);
        $result->execute();
        $this->dbLocalConnection=null;

        return $result->fetchAll();
    }

    public  function  closeConnection(){
        $this->app->closeConnection();
    }

public  function  getApplicationByName($name){
    $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
    $this->dbLocalConnection=$db->getConnection();

    //get users of application by name
    $result=$this->dbLocalConnection->prepare("select * from applications where  name = ?");
    $this->dbLocalConnection=null;
   if($result->execute(array($name))) {
       return $result->fetch();
   }else{
       return false;
   }


}
public function  createTableUsers($fields){


        try{


    $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
    $this->dbLocalConnection=$db->getConnection();
    $db=null;
    $table="users_".$this->app->getName();
    $sql=" CREATE TABLE $table (
     ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
     ADUsername VARCHAR( 255 ),";
    $lastElement=count($fields)-1;
   for($i=0;$i<count($fields)-1;$i++){
       $sql=$sql." ".$fields[$i]["name"]." varchar(255) ,";
   }
$sql=$sql." ".$fields[$lastElement]["name"]." varchar(255)  )";

$this->dbLocalConnection->exec($sql);
$this->dbLocalConnection=null;
        }catch (PDOException $ex){
            return false;
        }

}
public function addApplicationFields($fields,$idApp){

    try{


        $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
        $this->dbLocalConnection=$db->getConnection();
        $result = $this->dbLocalConnection->prepare("INSERT INTO application_fields (name, value,app_id) values (?, ?, ?)");
        for($i=0;$i<count($fields);$i++){
            $result->execute(array($fields[$i]['name'],$fields[$i]['value'],
                $idApp));
        }

    }catch (PDOException $ex){
        return false;
    }
}
    public function  fillTableUsers($fields){
        try{

            //$remoteConnection->query($this->app->getQuery());
            $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");

            $table="users_".$this->app->getName();
            $values=array();
            $names=array();
            for($i=0;$i<count($fields);$i++){
                array_push($values,"?");
                array_push($names,$fields[$i]['name']);
            }

            $sql="INSERT INTO $table (".implode(",", $names).") values (".implode(",", $values).")";
            $remoteConnection=$this->app->getConnection();




            $this->dbLocalConnection=$db->getConnection();
            $db=null;
            foreach ($remoteConnection->query($this->app->getQuery()) as $row){

              //get
               $data=array();
                for($i=0;$i<count($fields);$i++){
                    array_push($data,$row[$fields[$i]['value']]);

                }
                //build query insert users

              $result=  $this->dbLocalConnection->prepare($sql);
                $result->execute($data);
            }
$remoteConnection=null;
            $this->dbLocalConnection=null;

        }catch (PDOException $ex){

            return false;
        }
    }
    public function  getFieldsOfApplicationById($id){
        $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
        $this->dbLocalConnection=$db->getConnection();
        $result= $this->dbLocalConnection->prepare("select * from application_fields where app_id = ?");
        $result->execute(array($id));
        $this->dbLocalConnection=null;
        return $result->fetchAll();
    }
    public  function  getUsersHasADUsernameByApplication($name){
        $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
        $this->dbLocalConnection=$db->getConnection();

        //get users of application by name
        $result=$this->dbLocalConnection->prepare("select ADUsername,username from users_".$name." where ADUsername IS NOT NULL AND TRIM(IFNULL(ADUsername,'')) <> ''");
        $result->execute();
        $this->dbLocalConnection=null;
        return $result->fetchAll();
    }
public  function updateADUsername($appName,$id,$newValue){
    $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
    $this->dbLocalConnection=$db->getConnection();
    $result= $this->dbLocalConnection->prepare("update users_".$appName." SET  ADUsername = ? WHERE ID = ?");
    $result->execute(array($newValue,$id));
    $this->dbLocalConnection=null;
}
public function deleteApplication($name){
        $test=false;
    $db=new DatabaseConnection("127.0.0.1","root","","ooredoo","mysql","3306");
    $this->dbLocalConnection=$db->getConnection();
    $result= $this->dbLocalConnection->prepare(" DELETE FROM applications WHERE name = ?");

    $result->execute(array($name));
    if($result->rowCount()>0){
        $result= $this->dbLocalConnection->prepare(" DROP TABLE users_".$name);
       if($result->execute()){
           $test=true;
       }

        $this->dbLocalConnection=null;
    }
    return $test;

}
public function testQuery(){
        try{
            $conn=$this->app->getConnection();
            $conn->query($this->app->getQuery());
           $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
           $conn=null;
            return true;
        }catch (PDOException $ex){
            return false;
        }

}
public function testFields($fields){
    try{
        $test=true;
        $conn=$this->app->getConnection();
        $res=$conn->prepare($this->app->getQuery());
        $res->execute(array());
        $row=$res->fetch();


    for($i=0;$i<count($fields);$i++){
        if(!array_key_exists($fields[$i]["value"],$row)){
            $test=false;
            break;
        }
    }


$conn=null;

        return $test;
    }catch (PDOException $ex){
        return false;
    }
}
}