<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications'; // Specify the table name if it's different
    protected $primaryKey = 'id'; // Specify the primary key if it's different
    protected $fillable = ['id', 'type', 'notifiable_type', 'notifiable_id', 'data']; // Define fillable fields

}
