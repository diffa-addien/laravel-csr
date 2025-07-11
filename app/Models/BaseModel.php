<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

// Gunakan alias 'as' untuk menghindari konflik nama antara trait dan interface
use OwenIt\Auditing\Auditable as AuditableTrait; 

abstract class BaseModel extends Model implements Auditable
{
    // Gunakan trait yang sudah kita alias-kan
    use AuditableTrait;

    // Anda juga bisa menambahkan properti atau metode lain di sini
    // yang ingin Anda bagikan ke semua model Anda.
}