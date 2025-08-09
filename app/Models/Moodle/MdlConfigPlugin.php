<?php

namespace App\Models\Moodle;


use App\Builders\ConfigPluginBuilder;
use App\Scopes\MdlConfigPluginScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MdlConfigPlugin extends _BaseLMSModel
{
    use HasFactory;


    protected $table = 'config_plugins';

    public $timestamps = false;
    protected $fillable = [
        'name',
        'value'
    ];
    /*
     *  @return void
    */
    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new MdlConfigPluginScope());
    }

    public function newEloquentBuilder($query): ConfigPluginBuilder
    {
        return new ConfigPluginBuilder($query);
    }

    /**
     * Get the fa name.
     */
    protected function faName(): Attribute
    {
        return Attribute::make(
            get: fn() => __('access_quiz_plugin.'.$this->name)
        );
    }

}



