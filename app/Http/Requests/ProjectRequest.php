<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('get')) {
            return [
                'id' => 'required',
            ]; 
        }

        if ($this->isMethod('post')) {
            return [
                'title' => 'required|string|max:50|unique:projects',
            ];
        }

        if ($this->isMethod('put')) {
            return [
                'title' => 'required|string|max:50|unique:projects,title,'. request()->id,
            ];
        }

        if ($this->isMethod('delete')) {
            return [
                'id' => 'required',
            ]; 
        }

    }
}
