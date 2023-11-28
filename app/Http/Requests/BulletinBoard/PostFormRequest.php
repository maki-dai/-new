<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
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
            'post_category_id' => 'required|exists:sub_categories,id', // 登録されてるサブカテゴリーかどうか
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:5000',
        ];
    }

    public function messages(){
        return [
            'post_category_id.required' => 'サブカテゴリーは選択必須です。',
            'post_category_id.exists' => '存在しないサブカテゴリーです。',

            'post_title.required' => 'タイトルは必ず入力してください。',
            'post_title.string' => 'タイトルは文字列で入力してください。',
            'post_title.max' => 'タイトルは100文字以内で入力してください。',

            'post_body.required' => '投稿内容は必ず入力してください。',
            'post_body.string' => '投稿内容は文字列で入力してください。',
            'post_body.max' => '最大文字数は5000文字です。',
        ];
    }
}

// 編集機能のバリデーション
// post_title	'required|string|max:100'
// "・必須項目
// ・文字列型
// ・最大100文字"
// post_body	"・必須項目
// ・文字列型
// ・最大5000文字"
