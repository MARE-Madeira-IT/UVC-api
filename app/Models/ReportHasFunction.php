<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportHasFunction extends Model
{
    use SoftDeletes;

    protected $fillable = ["user", "function_id", "report_id"];
}
