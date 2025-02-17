<?php

namespace App\Models;

use CodeIgniter\Model;

class ZodiacModel extends Model
{
    protected $table = 'full_name_zodiac';
    protected $primaryKey = 'id';
    protected $allowedFields = ['full_name', 'zodiac'];
}
