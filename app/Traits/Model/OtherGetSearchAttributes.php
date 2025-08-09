<?php

namespace App\Traits\Model;

trait OtherGetSearchAttributes
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

             // ----------url-------
            'url_name' => $this->url_name,
            'externalurl' => $this->externalurl,
            'url_intro' => $this->url_intro,

            // ----------page-------
            'page_name' => $this->page_name,
            'page_intro' => $this->page_intro,
            'page_content' => $this->page_content,
            'modified_sort' => $this->modified_sort
        ];

        return array_map('to_standard_letter', $array);
    }
    public static function getSearchFilterAttributes(): array
    {
        return [
            'course_id',
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
            'externalurl',

            'url_name',
            'url_intro',

            'page_content',
            'page_name',
            'page_intro',
        ];
    }

    public static function getSearchCropAttributes(): array
    {
        return [
            'course_fullname',

            'externalurl',

            'url_name',
            'url_intro',

            'page_content',
            'page_name',
            'page_intro',
        ];
    }

    public static function getSearchRetrieveAttributes(): array
    {
        return [
            'id',
            'course_fullname',

            'externalurl',

            'url_name',
            'url_intro',

            'page_content',
            'page_name',
            'page_intro',

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
