<?php

namespace App\Controllers;


use App\Models\Application;
use App\Models\DatabaseConnection;
use App\Models\TypeOfFields;
use App\Repositories\ApplicationRepository;

class ApplicationsController extends Controller {


    public function index($request,$response,$args){
        //($name, $dbName, $host, $login, $password, $type, $port, $query, $username, $lastAuth, $status, $matricule, $lastModif)
$rep=new ApplicationRepository();
$apps=$rep->getApplications();
$users=$rep->getUsersOfApplicationById('crm');
$this->view->render($response,"home.twig",array("apps"=>$apps,"users"=>$users));
    }
    public function createApplication($request,$response,$args){
        if(!isset($_POST['submit'])){
            $this->view->render($response,"createApp.twig",array());
        }else{
            //$host, $username, $password, $database, $type, $port
            //get Parameters
            //($name, $dbName, $host, $login, $password, $type, $port, $query, $username, $lastAuth, $status, $matricule, $lastModif)
            $name=$_POST['name'];
            $dbName=$_POST['dbname'];
            $host=$_POST['ip'];
            $login=$_POST['login'];
            $password=$_POST['password'];
            $type=$_POST['type'];
            $port=$_POST['port'];
            $query=$_POST['query'];
            //get fields
            $username=$_POST['username'];
            $names=$_POST['fieldName'];
            $values=$_POST['fieldValue'];
            $types=$_POST['fieldType'];

            $app=new Application($name, $dbName, $host, $login, $password, $type, $port, $query);
            $rep=new ApplicationRepository($app);
            //test remote connection
            if($rep->testConnection()){
                $rep->closeConnection();
                //add app with success
                if($idApp=$rep->add()) {
                   //create table users for the new app
                    //get fields
                    $fields=array(array("name"=>"username","value"=>$_POST['username'],"type"=>"VARCHAR( 255 )"));
                    for($i=0;$i<count($names);$i++){
                        if((!empty($names[$i]))&&(!empty($values[$i]))){
                            array_push($fields,array("name"=>$names[$i],"value"=>$values[$i],"type"=>$types[$i]));
                        }
                    }
                 $rep->createTableUsers($fields);
                  $rep->addApplicationFields($fields,$idApp);
                    //fill the table users from the remote table
                   $rep->fillTableUsers($fields);
                    return $this->view->render($response,"createApp.twig",array());
                }else{

                    return $this->view->render($response,"createApp.twig",array("test"=>"App not Added"));
                }
            }
        }



    }
public  function displayUsersByApplication($request,$response,$args){
    $rep=new ApplicationRepository();
    $apps=$rep->getApplications();
    $app=$rep->getApplicationByName($args['name']);
    $users=$rep->getUsersOfApplicationById($args['name']);
    $fields=$rep->getFieldsOfApplicationById(33);

    $this->view->render($response,"home.twig",array("apps"=>$apps,"users"=>$users,"fields"=>$fields,"fields1"=>$fields));
}

}