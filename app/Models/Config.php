<?php

namespace App\Models;

/**
 * Class Config.
 *
 * @package namespace App\Models;
 *
 * @OA\Schema(schema="Config")
 *
 */
class Config extends _UuidModel
{
    const TBL = 'configs';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'scope_id'
    ];


    //set all columns as fillable
    protected $guarded = [];


    public static function get($scope, $scope_id, $key, $default = '', $create = false)
    {
        $func = $scope_id === '' ? 'firstOrCreate' : 'firstOrNew';
        if ($create) $func = 'firstOrCreate';

        $config = self::$func(
            [
                'scope' => $scope,
                'scope_id' => $scope_id,
                'name' => $key,
            ],
            [
                'value' => $default
            ]
        );

        return $config->value;
    }

    public static function getConfig($scope_id, $key, $default = '', $create = false)
    {
        return self::get(self::TBL, $scope_id, $key, $default, $create);
    }
    public static function getLastRun($scope_id, $key)
    {
        return self::where('scope',self::TBL)->where('scope_id',$scope_id)->where('name',$key)->value('value');
    }


    public static function set($scope, $scope_id, $key, $value)
    {
        $config = self::updateOrCreate(
            [
                'scope' => $scope,
                'scope_id' => $scope_id,
                'name' => $key
            ],
            [
                'value' => $value
            ]
        );

        return $config->value == $value;
    }
}
