<?php
namespace App\Contracts;

interface ISearchAttributes {
    public function toSearchableArray(): array;
    public static function getSearchFilterAttributes(): array;
    public static function getSearchSortAttributes(): array;
    public static function getSearchHighlightAttributes(): array;
    public static function getSearchCropAttributes(): array;
    public static function getSearchRetrieveAttributes(): array;
    public static function getStopWords(): array;
    public static function prepareFilters($filters): array;
    public static function prepareSort($sort): array;

}
