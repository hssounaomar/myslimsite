<?php

namespace App\Controllers;


use App\Models\Application;
use App\Models\DatabaseConnection;
use App\Models\TypeOfFields;
use App\Models\User;
use App\Repositories\ApplicationRepository;
use \mysqli;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Respect\Validation\Exceptions\EachException;

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

        if(!isset($_POST['name'])){
            $this->view->render($response,"createApp.twig",array());
        }else {
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
            $email=$_POST['email'];
            //get fields
            $username=$_POST['username'];
            $names=$_POST['fieldName'];
            $values=$_POST['fieldValue'];


            $app=new Application($name, $dbName, $host, $login, $password, $type, $port, $query,$email);
            $rep=new ApplicationRepository($app);
            //test remote connection
            if($rep->testConnection()){

                $rep->closeConnection();
                //test query
if($rep->testQuery()){
    //check if the given fields exists
    array_unshift($names,"username");
    array_unshift($values,$_POST['username']);
    $fields=array();
    for($i=0;$i<count($names);$i++){
        if((!empty($names[$i]))&&(!empty($values[$i]))){
            array_push($fields,array("name"=>$names[$i],"value"=>$values[$i]));

        }
    }
    if($rep->testFields($fields)){
        //add app with success
        if($idApp=$rep->add()) {

            //create table users for the new app
            //get fields

            $rep->createTableUsers($fields);
            $rep->addApplicationFields($fields,$idApp);
            //fill the table users from the remote table
            $rep->fillTableUsers($fields);
           echo "";
        }else{

            echo "Error, May this App already existed ";
        }
        //invalid fields
    }else{
echo "invalid fields";
    }
   //invalid query
}else{
echo "invalid query";
}
           //error connection
            }else{
echo "error connexion";
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
public  function  pageNotFound($request,$response,$args){
    $this->view->render($response,"errorNotFound.twig");
}public  function  errorNotAllowed($request,$response,$exception){
    $this->view->render($response,"errorNotAllowed.twig");
}
    /**
     *
     */
    public function  ajax($request,$response,$args){
        $rep=new ApplicationRepository();
        if(isset($_POST['id'])&&isset($_POST['newValue'])){
            $rep->updateADUsername($args['name'],$_POST['id'],$_POST['newValue']);
            echo "";
        }else{
            echo "false";
        }

      }
public  function  displayUsers($request,$response,$args){
    $rep=new ApplicationRepository();
    $apps=$rep->getApplications();
    $data=array();
    foreach ($apps as $app){
        $users=$rep->getUsersHasADUsernameByApplication($app['name']);
        if(!empty($users)){

            foreach ($users as $user){
                array_push($data,array("app"=>$app['name'],"username"=>$user['username'],"ADUsername"=>$user['ADUsername']));
            }

        }
    }


    return $this->view->render($response,"users.twig",array("data"=>$data,"apps"=>$apps));
}
public  function  generateUsersExcel($request,$response,$args){
    $rep=new ApplicationRepository();
    $apps=$rep->getApplications();
    $data=array(array("App","Username","ADUsername"));
    foreach ($apps as $app){
        $users=$rep->getUsersHasADUsernameByApplication($app['name']);
        if(!empty($users)){

            foreach ($users as $user){
                array_push($data,array($app['name'],$user['username'],$user['ADUsername']));
            }

        }
    }
    $spreadsheet = new Spreadsheet();
    $spreadsheet->getActiveSheet()
        ->fromArray(
            $data,  // The data to set
            NULL,        // Array values with this value will not be set
            'A1'         // Top left coordinate of the worksheet range where
        //    we want to set these values (default is A1)
        );
    for ($i = 'A'; $i !=  $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
        $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save('UsersApp.xlsx');
    echo "OK";
}
public function sendEmail($request,$response,$args){
        require __DIR__.'/../../vendor/SendEmail/PHPMailer-master/PHPMailerAutoload.php';
    require  __DIR__ . '/../../vendor/SendEmail/send_mail.php';
    $data = $_POST['msg'];
if(empty($data)){
    echo "Error";
}else{
if(is_array($data)){
    $rep=new ApplicationRepository();

    foreach ($data as $row){
        $app=$rep->getApplicationByName($row[0]);
        //sendMail($mailto,$mailSub,$mailMsg,$Username,$pwd)

        sendMail($app['email'],$row[0],"supp user ".$row[1],"omar.hsouna@sesame.com.tn","hsouna007");

    }
    echo "";
}else{
    echo "Error";
}



}
}
public function manageApplications($request,$response,$args){

}
public function deleteApplication($request,$response,$args){
    $rep=new ApplicationRepository();
echo $rep->deleteApplication($args['name']);
}
public  function  updateApplication($request,$response,$args){
    $rep = new ApplicationRepository();
    if(!isset($_POST['submit'])) {

        $app = $rep->getApplicationByName($args['name']);
        $fields = $rep->getFieldsOfApplicationById($app['id']);
        return $this->view->render($response, "updateApplication.twig", array("fields" => $fields, "app" => $app));
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
        $email=$_POST['email'];
        //get fields
        $username=$_POST['username'];
        $names=$_POST['fieldName'];
        $values=$_POST['fieldValue'];


        $app=new Application($name, $dbName, $host, $login, $password, $type, $port, $query,$email);
        $rep=new ApplicationRepository($app);
        //test remote connection
        if($rep->testConnection()){

            $rep->closeConnection();
            //delte app + table users of the application
$rep->deleteApplication($args['name']);
//add app with success
            if($idApp=$rep->add()) {

                //create table users for the new app
                //get fields
                $fields=array(array("name"=>"username","value"=>$_POST['username']));
                for($i=0;$i<count($names);$i++){
                    if((!empty($names[$i]))&&(!empty($values[$i]))){
                        array_push($fields,array("name"=>$names[$i],"value"=>$values[$i]));
                    }
                }

                $rep->createTableUsers($fields);
                $rep->addApplicationFields($fields,$idApp);
                //fill the table users from the remote table
                $rep->fillTableUsers($fields);
                return $response->withRedirect('/apps');
            }else{

                return $this->view->render($response,"updateApplication.twig",array());
            }
        }
    }
}


public function error($request, $response, $exception){
    return $this->view->render($response,"Error.twig",array());
}
public  function generateTableExcel($request,$response,$args){
    $rep=new ApplicationRepository();
   $users= $rep->getUsersOfApplicationByName($args['name']);
   $app= $rep->getApplicationByName($args['name']);
   $fields=$rep->getFieldsOfApplicationById($app['id']);
   $row1=array();
foreach ($fields as $field){
    $row1[]=$field['name'];
}
array_push($row1,'ADUsername');
//data
$rows=array();
   foreach ($users as $user){
$row=array();
foreach ($fields as $field){
    $row[]=$user[$field['name']];
}
$row[]=$user['ADUsername'];
$rows[]=$row;
   }
   array_unshift($rows,$row1);
    $spreadsheet = new Spreadsheet();
    $spreadsheet->getActiveSheet()
        ->fromArray(
            $rows,  // The data to set
            NULL,        // Array values with this value will not be set
            'A1'         // Top left coordinate of the worksheet range where
        //    we want to set these values (default is A1)
        );
    for ($i = 'A'; $i !=  $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
        $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save('appUsers.xlsx');
    echo "OK";
}
}