<?php

namespace App\Models;

use App\Traits\MultiTenancyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, MultiTenancyTrait;
}
