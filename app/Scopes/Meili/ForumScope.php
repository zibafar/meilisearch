<?php

namespace App\Scopes\Meili;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class ForumScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $mod="forum";
        return $builder->rightJoin('forum', function ($join) {
            $join->on('course' . '.id', '=', 'forum' . '.course');
        })

            ->rightJoin('course_modules', function ($join) use ($mod) {
                $join->on('course' . '.id', '=', 'course_modules' . '.course')
                    ->on($mod . '.id', '=', 'course_modules' . '.instance');
            })->rightJoin('modules', function ($join) use ($mod) {
                $join->on('course_modules' . '.module', '=', 'modules' . '.id')
                    ->on('modules' . '.name', '=' ,DB::raw("'".$mod."'"));
            })

            ->leftJoin('forum_discussions', function ($j) {
                $j->on('forum' . '.id', '=', 'forum_discussions' . '.forum');
            })
            ->leftJoin('forum_posts', function ($j) {
                $j->on('forum_discussions' . '.id', '=', 'forum_posts' . '.discussion');
            })
            ->select([
                //0=>course_id , forum_id=>1 discussion_id=>2 post_id=>3 mod_id=>4
                DB::raw("CONCAT( `mdl_course`.`id` ,
                                 '-', COALESCE(`mdl_forum`.`id`, 0),
                                 '-', COALESCE(`mdl_forum_discussions`.`id`, 0),
                                 '-', COALESCE(`mdl_forum_posts`.`id`, 0),
                                 '-', COALESCE(`mdl_course_modules`.`id`, 0)
                                 )

                    AS id"),
                DB::raw('mdl_course' . '.id AS course_id'),
                DB::raw('mdl_course' . '.fullname AS course_fullname'),
                DB::raw('mdl_forum' . '.id AS forum_id'),
                DB::raw('mdl_forum' . '.name AS forum_name'),
                DB::raw('mdl_forum' . '.intro AS forum_intro'),
                DB::raw('mdl_forum_discussions' . '.id AS discussion_id'),
                DB::raw('mdl_forum_discussions' . '.name AS discussion_name'),
                DB::raw('mdl_forum_posts' . '.id AS post_id'),
                DB::raw('mdl_forum_posts' . '.userid AS user_id'),
//                DB::raw('mdl_forum_posts'.'.parent AS parent'),
                DB::raw('mdl_forum_posts' . '.subject AS subject'),
                DB::raw('mdl_forum_posts' . '.message AS message'),

//                DB::raw('mdl_forum_posts'.'.deleted AS deleted'),
                DB::raw("GREATEST(
                                        COALESCE(`mdl_forum_posts`.`modified`,0) ,
                                        COALESCE(`mdl_forum_discussions`.`timemodified`,0),
                                        COALESCE(`mdl_forum`.`timemodified`,0),
                                        COALESCE(`mdl_course`.`timemodified`,0)
                                         )
                    AS modified"),
                DB::raw("COALESCE(
                                         `mdl_forum_posts`.`modified` ,
                                         `mdl_forum_discussions`.`timemodified`,
                                         `mdl_forum`.`timemodified`,
                                         `mdl_course`.`timemodified`)
                    AS modified_sort"),
//                DB::raw('forum_posts'.'.created AS created'),
//                DB::raw('mdl_forum_posts'.'.wordcount AS wordcount'),
//                DB::raw('mdl_forum_posts'.'.charcount AS charcount')
            ])->where('modules' . '.name', DB::raw("'".$mod."'"));
    }
}
