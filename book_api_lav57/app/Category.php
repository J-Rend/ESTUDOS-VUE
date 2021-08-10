<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 *
 * @package App
*/
class Category extends Model
{
    //public $timestamps = false;
    protected $table = 'category';
    protected $fillable = ['name','description'];
    
    protected $hidden = [];
    
}