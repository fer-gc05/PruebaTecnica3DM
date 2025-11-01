<?php

namespace App\Http\Requests\Results;

use App\Http\Requests\BaseRequest;

class UpdateResult extends BaseRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|string|max:255',
            'value' => 'sometimes|integer',
            'category' => 'sometimes|in:bad,medium,good',
            'timestamp' => 'sometimes|string|max:255',
            'ip_address' => 'sometimes|string|max:255',
            'attempts' => 'sometimes|integer',
        ];
    }
}
