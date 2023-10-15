<?php

namespace Modules\FrontendCMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchemeMarkups extends Model
{
    use HasFactory;

    protected $table = 'scheme_markups';
    protected $guarded = ['id'];
}
