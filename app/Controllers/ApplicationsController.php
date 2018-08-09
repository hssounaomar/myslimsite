<?php

namespace App\Controllers;


use App\Models\Application;
use App\Models\DatabaseConnection;
use App\Models\TypeOfFields;
use App\Repositories\ApplicationRepository;

use
    DataTables\Editor,
    DataTables\Editor\Field;
class ApplicationsController extends Controller {

public function __construct($container)
{
    parent::__construct($container);

}

    public function index($request,$response,$args){
        //($name, $dbName, $host, $login, $password, $type, $port, $query, $username, $lastAuth, $status, $matricule, $lastModif)
$rep=new ApplicationRepository();
$apps=$rep->getApplications();
$app=null;
$users=null;
$fields=null;
        if(!empty($apps)){
         $name=   $apps[0]['name'];
         $app=$apps[0];
            $users=$rep->getUsersOfApplicationByName($name);
            $fields=$rep->getFieldsOfApplicationById($app['id']);
}

$this->view->render($response,"home.twig",array("apps"=>$apps,"users"=>$users,"app"=>$app,"fields"=>$fields));
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
                    return $response->withRedirect('/apps');
                }else{

                    return $this->view->render($response,"createApp.twig",array());
                }
            }
        }



    }
public  function displayUsersByApplication($request,$response,$args){
    $rep=new ApplicationRepository();
    $app=null;
    //test if app exists
    if($app=$rep->getApplicationByName($args['name'])){
        $apps=$rep->getApplications();

        $users=$rep->getUsersOfApplicationByName($args['name']);
        $fields=$rep->getFieldsOfApplicationById($app['id']);

        $this->view->render($response,"home.twig",array("apps"=>$apps,"users"=>$users,"fields"=>$fields,"app"=>$app));
    }else{
        //else return to home
        return $response->withRedirect('/apps');
    }

}

    /**
     *
     */
    public function  ajax($request,$response,$args){
        require  __DIR__ . '/../../vendor/EditTable/DataTables.php';

// Build our Editor instance and process the data coming from _POST
        $rep=new ApplicationRepository();
        $app=$rep->getApplicationByName($args['name']);
        $fields=$rep->getFieldsOfApplicationById($app['id']);

        $test=array();

        foreach ($fields as $field){
            array_push($test,Field::inst($field['name']));

        }
        array_push($test,Field::inst( "ADUsername" ));
        $data = Editor::inst( $db, 'users_'.$args['name'] )->fields($test)->process( $_POST )
            ->data();


        if($request->isGet()){
            $data=  json_encode( $data ).":[]}";
            echo  $data;
        }
        if($request->isPost()){
            echo json_encode( $data );
        }


}
}