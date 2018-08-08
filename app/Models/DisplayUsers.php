<?php

namespace App\Models;
class DisplayUsers extends \Slim\Views\TwigExtension
{
    public function __construct()
    {
    }

    public function getName()
    {
        return 'DisplayUsers';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('DisplayUsers', array($this, 'DisplayUsers'))
        ];
    }

    public function DisplayUsers ($users,$fields)
    {
        $str="";

        foreach ($users as $user){
            $test=$fields;
            $str=$str."<tr>";
            foreach ($test as $field){

                $str=$str. "<td>".$user[$field['name']]."</td>";
            }
            $str=$str."<td>".$user['ADUsername']."</td></tr>";

        }
        echo $str;

    }
}