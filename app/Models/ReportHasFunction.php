<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportHasFunction extends Model
{

    protected $fillable = ["user", "function_id", "report_id"];
}
