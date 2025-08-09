<?php

namespace App\Traits\Model;

trait ForumGetSearchAttributes
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
            //----course---------
            'course_id' => $this->course_id,
            'course_fullname' => $this->course_fullname,
            //----post---
            'subject' => $this->subject,
            'message' => $this->message,
            //  ----discussion
            'discussion_name' => $this->discussion_name,
            //----forum---------
            'forum_name' => $this->forum_name,
            'forum_intro' => $this->forum_intro,

            'modified_sort' => $this->modified_sort
        ];

        return array_map('to_standard_letter', $array);
    }
    public static function getSearchFilterAttributes(): array
    {
        return [
            'discussion_id',
            'course_id',
//            'user_id',
            'forum_id',
            'modified',
            'modified_sort',
        ];
    }

    public static function getSearchSortAttributes(): array
    {
        return [
            'modified',  //GREATEST
            'modified_sort',//COALESCE
        ];
    }

    public static function getSearchHighlightAttributes(): array
    {
        return [
            'message',
            'subject',
            'discussion_name',

            'forum_name',
            'forum_intro',

        ];
    }

    public static function getSearchCropAttributes(): array
    {
        return [
            'course_fullname', 'message', 'forum_intro', 'discussion_name' ,'page_content'
        ];
    }

    public static function getSearchRetrieveAttributes(): array
    {
        return [
            'id',
            'message',
            'subject',
            'discussion_name',
            'forum_name',
            'forum_intro',
            'course_fullname',
            'modified_sort'
        ];
    }

    public static function getStopWords(): array
    {
        $stop=['از','یا','تا','را','و' ,'که','Re','https','http','www','.com'];
        return array_map('to_standard_letter',$stop);
    }

    public static function prepareFilters($filters): array
    {
        $result = [];
        if (!empty($filters['discussion_ids'] ?? '')) {
            $result[] = "discussion_id IN [ {$filters['discussion_ids']} ]";
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
