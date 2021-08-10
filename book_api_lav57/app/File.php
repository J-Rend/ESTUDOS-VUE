<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 *
 * @package App
*/
class File extends Model
{
    public $timestamps = false;
    protected $table = 'file';
    protected $fillable = ['name',
'type',
'table',
'title',
'size',
'id_registry',
'old_name',
'thumbs',
'pid',];
    protected $hidden = [];
    
}
