<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=>'required',
            'path'=>'required',
            'group_id' => 'required|exists:groups,id',
            'user_id' => 'required|exists:users,id',
            'Active'=>'required',
            'Name_File'=>'required',
            'description'=>'required',
        ];
    }
}
