<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

/**
 * App\Models\_UuidModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|_UuidModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|_UuidModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|_UuidModel query()
 * @mixin \Eloquent
 */
class _UuidModel extends Model
{
    use UsesUuid;

    //set all columns as fillable
    protected $guarded = [];

    /**
     * @return array|mixed
     */
    public function getColumns()
    {
        $seconds = 3600 * 24;
        $tbl = $this->getTable();
        $columns = Cache::remember($tbl . '-columns', $seconds, function () use ($tbl) {
            return Schema::getColumnListing($tbl);
        });

        return $columns;
        //$columns = array_fill_keys($columns, null);
    }

    /**
     * @return string
     */
    public function hashColumns()
    {
        $columns = array_fill_keys($this->getColumns(), null);
        //Expected TimeStamp
        unset($columns['updated_at']);
        unset($columns['created_at']);
        unset($columns['updated_by']);
        unset($columns['created_by']);
        unset($columns['hash']);
        $str = '';

        if (count($columns) > 0) {
            foreach ($columns as $key) {
                $str .= $this->getAttribute($key);
            }
        }

        return hash('sha256', $str . '+*', false);
    }
}
