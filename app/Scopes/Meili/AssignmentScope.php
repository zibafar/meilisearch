<?php

namespace App\Scopes\Meili;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class AssignmentScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $mod = 'assign';
        return $builder->rightJoin($mod, function ($join) {
                $join->on('course' . '.id', '=', 'assign' . '.course');
            })->rightJoin('course_modules', function ($join) use ($mod) {
                $join->on('course' . '.id', '=', 'course_modules' . '.course')
                    ->on($mod . '.id', '=', 'course_modules' . '.instance');
            })->rightJoin('modules', function ($join) use ($mod) {
                $join->on('course_modules' . '.module', '=', 'modules' . '.id')
                    ->on('modules' . '.name', '=' ,DB::raw("'".$mod."'"));
            })
            ->select([
                DB::raw("CONCAT( `mdl_course`.`id`, '-', COALESCE(`mdl_assign`.`id`, 0), '-', COALESCE(`mdl_course_modules`.`id`, 0)) AS id"),
                DB::raw('mdl_course_modules' . '.id AS cm_id'),
                DB::raw('mdl_course' . '.id AS course_id'),
                DB::raw('mdl_course' . '.fullname AS course_fullname'),
                DB::raw('mdl_assign' . '.id AS assign_id'),
                DB::raw('mdl_assign' . '.name AS assign_name'),
                DB::raw('mdl_assign' . '.intro AS assign_intro'),
//
                DB::raw("GREATEST(
                                         COALESCE(`mdl_assign`.`timemodified`,0) ,
                                         COALESCE(`mdl_course`.`timemodified`,0)
                                         )
                    AS modified"),
                DB::raw("COALESCE(`mdl_assign`.`timemodified` ,
                                         `mdl_course`.`timemodified`)
                    AS modified_sort"),

            ])->where(function ($query) {
                $query->whereNotNull('assign' . '.name' )
                    ->orWhereNotNull('assign' . '.intro');
            })->where('modules' . '.name', DB::raw("'".$mod."'"));
    }
}
