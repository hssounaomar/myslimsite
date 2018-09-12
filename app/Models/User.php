<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 27/08/2018
 * Time: 16:48
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
protected $table ='users';
protected $fillable =['email','name','password'];
}