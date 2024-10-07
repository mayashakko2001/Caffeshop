<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table='reports';
    protected $date_upload = ['date_for_upload'];
    protected $date_delete = ['date_for_delete'];
    protected $date_update = ['date_for_update'];
    protected $date_booking = ['date_for_booking'];
    protected $fillable = ['discreption_report','file_id','date_for_upload','date_for_delete','date_for_update','date_for_booking'];
    
     
    public function file(){
        return $this->belongsTo(File::class,'file_id');
      }
    
}
