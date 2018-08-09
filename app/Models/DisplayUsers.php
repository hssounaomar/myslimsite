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
            new \Twig_SimpleFunction('DisplayFields', array($this, 'DisplayFields'))
        ];
    }

    public function DisplayFields ($fields)
    {
        $str="[";

        foreach ($fields as $field){

$str=$str. '{
                        label: "{{ '.$field['name'].' }}:",
                        name: "{{ '.$field['name'].' }}"
                    },';


        }
        $str=$str.'{
        label: "AuthenticUsername:",
                        name: "ADUsername"
                    }]';
        echo $str;

    }
}