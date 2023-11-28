<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryFormRequest extends FormRequest
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
            //
            'sub_category' => 'required|string|max:100|unique:sub_categories',
            'main_category_id' => 'required|exists:main_categories,id',
            // main_category_id	"・必須項目
// ・登録されているメインカテゴリーか"

        ];
    }

        public function messages(){
        return [

          'sub_category.required'=>'サブカテゴリーは必須項目です。',
          'sub_category.string'=>'サブカテゴリーは文字列型で入力してください。',
          'sub_category.max'=>'サブカテゴリーは100文字以内で入力してください。',
          'sub_category.unique'=>'このサブカテゴリーは既に登録されています',
          'main_category_id.required'=>',メインカテゴリを選択してください。',
          'main_category_id.exists'=>'選択されたメインカテゴリは存在しません'
        ];
    }
}
