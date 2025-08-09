<?php

namespace App\Scopes\Meili;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class VideoScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $mod = 'fstream';
        return $builder->rightJoin($mod, function ($join) use ($mod) {
            $join->on('course' . '.id', '=', $mod . '.course');

        })

            ->rightJoin('course_modules', function ($join) use ($mod) {
            $join->on('course' . '.id', '=', 'course_modules' . '.course')
                ->on($mod . '.id', '=', 'course_modules' . '.instance');
        })->rightJoin('modules', function ($join) use ($mod) {
            $join->on('course_modules' . '.module', '=', 'modules' . '.id')
                ->on('modules' . '.name', '=' ,DB::raw("'".$mod."'"));
        })


            ->rightJoin($mod. '_bookmarks', function ($j) use ($mod) {
            $j->on($mod . '.id', '=', $mod. '_bookmarks' . '.' . $mod)
                ->where('class', 'mark');
        })
            ->select([
                DB::raw("CONCAT( `mdl_course`.`id` ,
                                 '-', COALESCE(`mdl_".$mod."`.`id`, 0),
                                 '-', COALESCE(`mdl_".$mod."_bookmarks`.`id`, 0),
                                 '-', COALESCE(`mdl_course_modules`.`id`, 0)
                                 )
                    AS id"),
                DB::raw('mdl_course' . '.id AS course_id'),
                DB::raw('mdl_course' . '.fullname AS course_fullname'),
                DB::raw('mdl_'.$mod.'.id AS video_id'),
                DB::raw('mdl_'.$mod.'.name AS video_name'),
                DB::raw('mdl_'.$mod.'.videostream AS video_stream'),
                DB::raw('mdl_'.$mod.'_bookmarks' . '.id AS bookmark_id'),
                DB::raw('mdl_'.$mod.'_bookmarks' . '.text AS bookmark_text'),
                DB::raw('mdl_'.$mod.'_bookmarks' . '.time AS bookmark_time'),
                DB::raw('mdl_'.$mod.'_bookmarks' . '.user AS user_id'),
//
                DB::raw("GREATEST(
                                         COALESCE(`mdl_".$mod."_bookmarks`.`timemodified`,0) ,
                                         COALESCE(`mdl_".$mod."`.`timemodified`,0),
                                         COALESCE(`mdl_course`.`timemodified`,0)
                                         )
                    AS modified"),
                DB::raw("COALESCE( `mdl_".$mod."_bookmarks`.`timemodified` ,
                                         `mdl_".$mod."`.`timemodified`,
                                         `mdl_course`.`timemodified`)
                    AS modified_sort"),

            ])->where('modules' . '.name', DB::raw("'".$mod."'"));
    }
}
