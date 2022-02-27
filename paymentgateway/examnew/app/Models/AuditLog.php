<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog  extends Model
{

    protected $table = 'audit_logs';
    protected $fillable = ['user_id', 'ip_address', 'mac_address'];
}
