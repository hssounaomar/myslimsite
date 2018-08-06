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
        $username=$_POST['username'];
        $lastAuth=$_POST['lastAuth'];
        $status=$_POST['status'];
        $matricule=$_POST['matricule'];
        $lastModif=$_POST['lastUpdate'];
        $app=new Application($name, $dbName, $host, $login, $password, $type, $port, $query, $username, $lastAuth, $status, $matricule, $lastModif);
        $rep=new ApplicationRepository($app);
        //test remote connection
        if($rep->testConnection()){
            $rep->closeConnection();
            //add app with success
           if($rep->add()) {

$rep->createTableUsers();
               $rep->fillTableUsers();
               return $this->view->render($response,"home.twig",array("App added"));
           }else{

               return $this->view->render($response,"home.twig",array("test"=>"App not Added"));
           }
        }

    }
public  function displayUsersByApplication($request,$response,$args){
    $rep=new ApplicationRepository();
    $apps=$rep->getApplications();
    $users=$rep->getUsersOfApplicationById($args['name']);
$app=$rep->getApplicationByName($args['name']);
    $this->view->render($response,"home.twig",array("apps"=>$apps,"users"=>$users,"app"=>$app));
}

}