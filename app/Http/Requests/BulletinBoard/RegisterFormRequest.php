<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class RegisterFormRequest extends FormRequest
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
        // !!正規表現どうにかする！regaxは[]で囲った方がいい！
        // inを使った選択肢から選ぶバリデーションが必要？
        return [
          'over_name'=>'required|string|max:10',
          'under_name'=>'required|string|max:10',
          'over_name_kana'=>['required','string','regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u','max:30'],
          'under_name_kana'=>[ 'required','string','regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u','max:30'],
          'mail_address'=>'required|email|unique:users|max:100',
          'sex'=>['required','in:1,2,3'],
        // 今日の指定ができない。。
          'old'=>'required|date|between:1999-12-31,today|',
            // Rule::notIn([Carbon::createFromDate(2000, 1, 1)->format('Y-m-d'), Carbon::today()->addDay()->format('Y-m-d')]),

          'role'=>['required','in:1,2,3,4'],
          'password'=>'required|between:8,30|confirmed',

 ];
    }

     protected function prepareForValidation()
    {
        $data = [];
        $data['old'] = sprintf('%04d-%02d-%02d', $this->old_year, $this->old_month, $this->old_day);
        // if ($data['old'] == '0000-00-00') $data['old'] = null;

        $this->merge($data);
    }
//   protected $rules = [
//         'birthday_year' => 'required|integer|date_format:Y',
//         'birthday_month' => 'required|between:1,12',
//         'birthday_day' => 'required|between:1,31',
//     ];

    // protected function withValidator($validator): void
    // {
    //     $validator->after(function ($validator) {
    //         if (!checkdate($this->input('old_month'), $this->input('old_day'), $this->input('old_year'))) {
    //             $validator->errors()->add('old_year_month_day', '正しい日付を入力してください');
    //         }
    //     });
    // }
// }

    //     protected function prepareForValidation()
    // {
    //     $year_month_day = ($this->filled(['old_year', 'old_month','old_day'])) ? $this->old_year .'-'. $this->old_month .'-'. $this->old_date : '';
    //     $this->merge([
    //        'old_year_month_day'=> $year_month_day
    //     ]);
    // }





    public function messages(){
        return [
// ここに各エラー文記述　　

           'over_name.required'=>'姓が未入力です。',
           'over_name.string'=>'姓は文字列型で入力してください。',
           'over_name.max'=>'姓は10文字以内で入力してください。',

           'under_name.required'=>'名が未入力です。',
           'under_name.string'=>'名は文字列型で入力してください。',
           'under_name.max'=>'名は10文字以内で入力してください。',

           'over_name_kana.required'=>'姓 カナが未入力です。',
           'over_name_kana.string'=>'姓 カナは文字列型で入力してください。',
           'over_name_kana.regex'=>'姓 カナはカタカナで入力してください。',
           'over_name_kana.max'=>'姓 カナは30文字以内で入力してください。',

           'under_name_kana.required'=>'名 カナが未入力です。',
           'under_name_kana.string'=>'名 カナは文字列型で入力してください。',
           'under_name_kana.regex'=>'名 カナはカタカナで入力してください。',
           'under_name_kana.max'=>'名 カナは30文字以内で入力してください。',

           'mail_address.required'=>'メールアドレスは未入力です。',
           'mail_address.email'=>'メールアドレスはメール形式で入力してください。',
           'mail_address.unique'=>'このメールアドレスは既に登録されています。',
           'mail_address.max'=>'メールアドレスは100文字以内で入力してください。',

           'sex.required'=>'性別が未選択です。',
           'sex.in'=>'性別は選択肢の中から選択してください。',

        //      'old'=>'date',
        //   'old_year'=>'required_with:old_month,old_day',
        //   'old_month'=>'required_with:old_year,old_day',
        //   'old_day'=>'required_with:old_year,old_month',
        //    'old.date'=>'生年月日は正しい日付で入力してください。',
        //    'old_year.required'=>'生年月日が未入力です。',
        //    'old_month.required'=>'生年月日が未入力です。',
        //    'old_day.required'=>'生年月日が未入力です。',
        //    'old_year_month_day'=>'date|after_or_equal:
        //    'old_year_month_day.required'=>'生年月日が未入力です。',
        //    'old_year_month_day.date'=>'生年月日は正しい日付で入力してください。',
        //    'old_year_month_day.after'=>'生年月日は2000年1月1日以降で入力してください。',

           'old.required'=>'生年月日が未入力です。',
           'old.date'=>'生年月日は正しい日付で入力してください。',
           'old.between'=>'生年月日は2000年1月1日から今日までの日付で入力してください。',
        //    'old.before'=>'生年月日は2000年1月1日から今日までの日付で入力してください。',


           'role.required'=>'権限は選択必須です',
           'role.in'=>'役職は選択肢の中から選択してください。',

           'password.required'=>'パスワードは入力必須です。',
           'password.between'=>'パスワードは8文字から30文字の間で入力してください。',
           'password.confirmed'=>'パスワードと確認用パスワードが一致していません。',

        ];
    }
}

 // フォームリクエストでのバリデーション
// over_name	"・必須項目
// ・文字列の型
// ・10文字以下"
// under_name	"・必須項目
// ・文字列の型
// ・10文字以下"
// over_name_kana	"・必須項目
// ・文字列の型
// ・カタカナのみ
// ・30文字以下"
// under_name_kana	"・必須項目
// ・文字列の型
// ・カタカナのみ
// ・30文字以下"
// mail_address	"・必須項目
// ・メールアドレスの形式
// ・登録済みのもの無効
// ・100文字以下"
// sex	"・必須項目
// ・男性、女性、その他以外無効"
// old_year	"・必須項目
// ・2000年1月1日から今日まで
// ・正しい日付かどうか（例:2/31や6/31はNG）"
// old_month
// old_day
// role	"・必須項目
// ・講師(国語)、講師(数学)、教師(英語)、生徒以外無効"
// password	"・必須項目
// ・8文字以上30文字以下
// ・確認用と同じかどうか"
