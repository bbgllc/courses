<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
        $trans = $this->post->translations()->where('locale', $this->lang)->first();
        
        if(!is_null($trans)){
            return [
                'title' => 'required|string',
                'slug' => 'required|string|no_spaces_allowed|unique_with:post_translations,locale,'.$trans->id,
                'intro' => 'required|string|min:25|max:500',
                'body' => 'required',
            ];
        } else {    
            return [
                'title' => 'required|string',
                'slug' => 'required|string|no_spaces_allowed|unique_with:post_translations,locale',
                'intro' => 'required|string|min:25|max:500',
                'body' => 'required',
            ];
        }
    }
    
}
