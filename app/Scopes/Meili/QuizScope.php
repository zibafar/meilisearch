<?php

namespace App\Scopes\Meili;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class QuizScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
//JOIN `mdl_quiz_slots` ON `mdl_quiz_slots`.`quizid` = `mdl_quiz`.`id`
//JOIN `mdl_question` ON `mdl_quiz_slots`.`slot` = `mdl_question`.id
//JOIN `mdl_question_answers` ON `mdl_question_answers`.`question` = `mdl_question`.id;

        $mod="quiz";
        //course -> Quiz ->quiz_slots-> Question -> Question_answer
        return $builder->rightJoin('quiz', function ($join)  {
            $join->on('course' . '.id', '=', 'quiz' . '.course');

        })
            ->rightJoin('course_modules', function ($join) use ($mod) {
                $join->on('course' . '.id', '=', 'course_modules' . '.course')
                    ->on($mod . '.id', '=', 'course_modules' . '.instance');
            })->rightJoin('modules', function ($join) use ($mod) {
                $join->on('course_modules' . '.module', '=', 'modules' . '.id')
                    ->on('modules' . '.name', '=' ,DB::raw("'".$mod."'"));
            })

            ->rightJoin('quiz_slots', function ($j) {
            $j->on('quiz_slots' . '.quizid', '=', 'quiz' . '.id');
        })
            ->leftJoin('question', function ($j) {
                $j->on('quiz_slots' . '.slot', '=', 'question' . '.id');
            })
            ->leftJoin('question_answers', function ($j) {
                $j->on('question_answers' . '.question', '=', 'question' . '.id');
            })
            ->select([
                DB::raw( "CONCAT( `mdl_course`.`id` ,
                                 '-', COALESCE(`mdl_quiz`.`id`, 0),
                                 '-', COALESCE(`mdl_quiz_slots`.`id`, 0),
                                 '-', COALESCE(`mdl_question`.`id`, 0),
                                 '-', COALESCE(`mdl_question_answers`.`id`, 0),
                                 '-', COALESCE(`mdl_course_modules`.`id`, 0)
                                 )
                    AS id"),
                DB::raw('mdl_course'.'.id AS course_id'),
                DB::raw('mdl_course'.'.fullname AS course_fullname'),

                DB::raw('mdl_quiz'.'.id AS quiz_id'),
                DB::raw('mdl_quiz'.'.name AS quiz_name'),
                DB::raw('mdl_quiz'.'.intro AS quiz_intro'),
                DB::raw('mdl_quiz'.'.timeopen AS timeopen'),
                DB::raw('mdl_quiz'.'.timeclose AS timeclose'),

                DB::raw('mdl_question'.'.id AS question_id'),
                DB::raw('mdl_question'.'.name AS question_name'),
                DB::raw('mdl_question'.'.questiontext AS question_text'),

                DB::raw('mdl_question_answers'.'.answer AS answer'),

                DB::raw( "GREATEST(
                                        COALESCE(`mdl_question`.`timemodified`,0),
                                        COALESCE(`mdl_quiz`.`timemodified`,0),
                                        COALESCE(`mdl_course`.`timemodified`,0)
                                         )
                    AS modified"),
                DB::raw( "COALESCE(
                                         `mdl_question`.`timemodified` ,
                                         `mdl_quiz`.`timemodified`,
                                         `mdl_course`.`timemodified`)
                    AS modified_sort"),
            ])->where('modules' . '.name', DB::raw("'".$mod."'"));
    }
}
