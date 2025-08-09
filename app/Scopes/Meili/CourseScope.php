<?php

namespace App\Scopes\Meili;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class CourseScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->leftJoin('course_categories', function ($join)  {
            $join->on('course_categories' . '.id', '=', 'course' . '.category');
        })->select([
                DB::raw( "CONCAT( `mdl_course`.`id` ,
                                 '-', COALESCE(`mdl_course_categories`.`id`, 0))
                    AS id"),
                DB::raw('mdl_course'.'.id AS course_id'),
                DB::raw('mdl_course'.'.shortname AS course_shortname'),
                DB::raw('mdl_course'.'.fullname AS course_fullname'),
                DB::raw('mdl_course'.'.summary AS course_summary'),
                DB::raw('mdl_course_categories'.'.id AS category_id'),
                DB::raw('mdl_course_categories'.'.name AS category_name'),
                DB::raw('mdl_course_categories'.'.description AS category_description'),
                DB::raw('mdl_course'.'.startdate AS startdate'),
                DB::raw('mdl_course'.'.enddate AS enddate'),

                DB::raw( "GREATEST(
                                        COALESCE(`mdl_course_categories`.`timemodified`,0),
                                        COALESCE(`mdl_course`.`timemodified`,0)
                                         )
                    AS modified"),
                DB::raw( "COALESCE(
                                         `mdl_course_categories`.`timemodified` ,
                                         `mdl_course`.`timemodified`
                                         )
                    AS modified_sort"),
            ]);
    }
}
