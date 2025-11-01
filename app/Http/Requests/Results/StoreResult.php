<?php

namespace App\Http\Requests\Results;

use App\Http\Requests\BaseRequest;

class StoreResult extends BaseRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|string|max:255',
            'value' => 'required|integer',
            'category' => 'required|in:bad,medium,good',
            'timestamp' => 'required|string|max:255',
            'ip_address' => 'required|string|max:255',
            'attempts' => 'required|integer',
        ];
    }
}
