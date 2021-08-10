<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Book
 *
 * @package App
*/
class Book extends Model
{
    public $timestamps = false;
    protected $table = 'book';
    protected $fillable = ['title',
'description',
'isbn',
'stock',
'author_id',
'price',
'editor',
'date_release',];
    protected $hidden = [];
    
}
