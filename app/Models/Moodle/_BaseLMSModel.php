<?php

namespace App\Models\Moodle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class _BaseLMSModel extends Model
{
    protected $connection = 'mysql_moodle';
    protected string $prefix;
    const MARKER_OPEN='<span class="highlight-search">';
    const MARKER_CLOSE='</span>';

    public function getPrefix(): string
    {
        return DB::connection($this->connection)->getTablePrefix();
    }


}
