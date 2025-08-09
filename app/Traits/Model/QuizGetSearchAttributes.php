<?php

namespace App\Traits\Model;

trait QuizGetSearchAttributes
{

    /**
     * تمام فیلدهایی که میلیسرچ باید در دیتابیس خودش ذخیره و ایندکس کند
     * چه برای سرچ
     * چه برای سورت
     * چه برای نمایش و هایلایت
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        $array = [
            "id" => $this->id,
            'course_id' => $this->course_id,
            "course_fullname" => $this->course_fullname,
//            "quiz_id"=>$this->quiz_id ,
            "quiz_name" => $this->quiz_name,
            "quiz_intro" => $this->quiz_intro,
//            "timeopen"=>$this->timeopen,
//            "timeclose"=>$this->timeclose,
//            "question_id"=>$this->question_id ,
            "question_name" => $this->question_name,
            "question_text" => $this->question_text,
            "answer" => $this->answer,
//            "modified" =>$this->modified ,
            "modified_sort"=>$this->modified_sort
        ];

        return array_map('to_standard_letter', $array);
    }

    public static function getSearchFilterAttributes(): array
    {
        return [
            'quiz_id',
            'course_id',
            'question_id',
            'timeopen',
            'timeclose',
            'modified_sort',

        ];
    }

    public static function getSearchSortAttributes(): array
    {
        return [
            'modified',
            'modified_sort',
        ];
    }

    public static function getSearchHighlightAttributes(): array
    {
        return [
            "quiz_name" ,
            "quiz_intro",

            "question_name" ,
            "question_text" ,

            "answer" ,
        ];
    }

    public static function getSearchCropAttributes(): array
    {
        return [
            "course_fullname" ,

            "quiz_name" ,
            "quiz_intro",

            "question_name" ,
            "question_text" ,

            "answer" ,
        ];
    }

    public static function getSearchRetrieveAttributes(): array
    {
        return [
            'id',
            "answer" ,

            "question_name" ,
            "question_text" ,

            "quiz_name" ,
            "quiz_intro",

            "course_fullname" ,

            'modified_sort'
        ];
    }

    public static function getStopWords(): array
    {
        $stop = ['از', 'یا', 'تا', 'را', 'و', 'که', 'Re'];
        return array_map('to_standard_letter', $stop);
    }

    public static function prepareFilters($filters): array
    {
        $result = [];
        if (!empty($filters['quiz_ids'] ?? '')) {
            $result[] = "quiz_id IN [ {$filters['quiz_ids']} ]";
        }
        if (!empty($filters['course_ids'] ?? '')) {
            $result[] = "course_id IN [ {$filters['course_ids']} ]";
        }
        return [implode(' AND ', $result)];
    }

    public static function prepareSort($sort): array
    {
        $sort = extractSort($sort);
        return [$sort['column'] . ':' . $sort['direction'], 'modified_sort:' . $sort['direction']];

    }
}
