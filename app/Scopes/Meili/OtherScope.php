<?php

namespace App\Scopes\Meili;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class OtherScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $mod="page";
        return $builder
        ->leftJoin('page', function ($j) {
            $j->on('course' . '.id', '=', 'page' . '.course');

        })

            ->rightJoin('course_modules', function ($join) use ($mod) {
                $join->on('course' . '.id', '=', 'course_modules' . '.course')
                    ->on($mod . '.id', '=', 'course_modules' . '.instance');
            })->rightJoin('modules', function ($join) use ($mod) {
                $join->on('course_modules' . '.module', '=', 'modules' . '.id')
                    ->on('modules' . '.name', '=' ,DB::raw("'".$mod."'"));
            })

            ->leftJoin('url', function ($j) {
            $j->on('course' . '.id', '=', 'url' . '.course');
        })
            ->select([
                DB::raw("CONCAT( `mdl_course`.`id` ,
                                 '-', COALESCE(`mdl_url`.`id`, 0),
                                 '-', COALESCE(`mdl_page`.`id`, 0),
                                 '-', COALESCE(`mdl_course_modules`.`id`, 0)
                                 )
                    AS id"),
                DB::raw('mdl_course' . '.id AS course_id'),
                DB::raw('mdl_course' . '.fullname AS course_fullname'),


                // ---url ----
                DB::raw('mdl_url' . '.name AS url_name'),
                DB::raw('mdl_url' . '.externalurl AS externalurl'),
                DB::raw('mdl_url' . '.intro AS url_intro'),

                // ---page ----
                DB::raw('mdl_page' . '.name AS page_name'),
                DB::raw('mdl_page' . '.content AS page_content'),
                DB::raw('mdl_page' . '.intro AS page_intro'),

//                DB::raw('mdl_forum_posts'.'.deleted AS deleted'),
                DB::raw("GREATEST(
                                       COALESCE(`mdl_page`.`timemodified`,0) ,
                                        COALESCE(`mdl_url`.`timemodified`,0) ,
                                        COALESCE(`mdl_course`.`timemodified`,0)
                                         )
                    AS modified"),
                DB::raw("COALESCE(
                                         `mdl_url`.`timemodified`,
                                          `mdl_page`.`timemodified`,
                                         `mdl_course`.`timemodified`)
                    AS modified_sort"),

            ]);
    }
}
