<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TechnicalInformation
 *
 * @package App
*/
class TechnicalInformation extends Model
{
    public $timestamps = false;
    protected $table = 'technical_information';
    protected $fillable = ['id_book',
'page_number',
'weight',];
    protected $hidden = [];
    
}
