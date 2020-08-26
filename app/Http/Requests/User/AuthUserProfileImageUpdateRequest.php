<?php

namespace App\Http\Requests\User;

use App\Models\MongoDB\Simulation\Simulation;
use App\Models\MongoDB\User\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthUserProfileImageUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'profile_image' => 'mimes:jpeg,jpg,png'
        ];
    }
}
