<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Author
 *
 * @package App
*/
class Author extends Model
{
    //public $timestamps = false;
    protected $table = 'author';
    protected $fillable = ['name',];
    protected $hidden = [];
    
}
