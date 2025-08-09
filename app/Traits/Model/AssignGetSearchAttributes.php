<?php

namespace App\Traits\Model;

trait AssignGetSearchAttributes
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

            'course_id' => $this->course_id,
            'course_fullname' => $this->course_fullname,

            'assign_name' => $this->assign_name,
            'assign_intro' => $this->assign_intro,
            'modified_sort' => $this->modified_sort
        ];

        return array_map('to_standard_letter', $array);
    }

    public static function getSearchFilterAttributes(): array
    {
        return [
            'course_id',
            'user_id',
            'modified',
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
            'assign_name',
            'assign_intro'
        ];
    }

    public static function getSearchCropAttributes(): array
    {
        return [
            'course_fullname',

            'assign_name',
            'assign_intro'
        ];
    }

    public static function getSearchRetrieveAttributes(): array
    {
        return [
            'id',
            'course_fullname',

            'assign_name',
            'assign_intro',

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
        if (!empty($filters['assign_ids'] ?? '')) {
            $result[] = "assign_id IN [ {$filters['assign_ids']} ]";
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
