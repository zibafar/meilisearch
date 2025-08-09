<?php

namespace App\Traits\Model;

trait CourseGetSearchAttributes
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
            "course_shortname" => $this->course_shortname,
            "course_fullname" => $this->course_fullname,
            "course_summary" => $this->course_summary,

            "category_name" => $this->category_name,
            "category_description" => $this->category_description,

//            "modified" =>$this->modified ,
            "modified_sort"=>$this->modified_sort
        ];

        return array_map('to_standard_letter', $array);
    }

    public static function getSearchFilterAttributes(): array
    {
        return [
            'course_id',
            'category_id',
            'startdate',
            'enddate',
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
            "course_shortname" ,
            "course_fullname" ,
            "course_summary" ,

            //"category_name" ,
            //"category_description",
        ];
    }

    public static function getSearchCropAttributes(): array
    {
        return [
            "course_shortname" ,
            "course_fullname" ,
            "course_summary" ,

            "category_name" ,
            "category_description",
        ];
    }

    public static function getSearchRetrieveAttributes(): array
    {
        return [
            'id',
            "course_shortname" ,
            "course_fullname" ,
            "course_summary" ,

            "category_name" ,
            "category_description",

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
        if (!empty($filters['category_ids'] ?? '')) {
            $result[] = "category_id IN [ {$filters['category_ids']} ]";
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
