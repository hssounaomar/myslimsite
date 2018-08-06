<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 31/07/2018
 * Time: 11:05
 */

namespace App\Models;


abstract class TypeOfFields extends  MyEnum
{
const username ="varchar(255)";
const last_auth="datetime";
const last_update="datetime";
const matricule="varchar(255)";
const status ="tinyint(1)";
}