<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class BlogPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'slug' => 'required|unique:blog_posts',
            'content' => 'required',
            'file.*' => 'nullable|mimes:jpg,jpeg,bmp,png',
//            'categories' => 'required',
            'tag' => 'required'  
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
