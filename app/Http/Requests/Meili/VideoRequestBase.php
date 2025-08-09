<?php

namespace App\Http\Requests\Meili;



use App\Http\Requests\BaseMeiliRequest;

class VideoRequestBase extends BaseMeiliRequest
{
    public function rules(): array
    {
        return parent::rules();
    }

}
