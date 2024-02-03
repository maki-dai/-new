@extends('layouts.sidebar')

@section('content')

<div class="search_content w-100 border d-flex">
  <div class="reserve_users_area">
    @foreach($users as $user)
    <div class="border one_person">
      <div>
        <span>ID : </span><span>{{ $user->id }}</span>
      </div>
      <div><span>名前 : </span>
        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
          <span>{{ $user->over_name }}</span>
          <span>{{ $user->under_name }}</span>
        </a>
      </div>
      <div>
        <span>カナ : </span>
        <span>({{ $user->over_name_kana }}</span>
        <span>{{ $user->under_name_kana }})</span>
      </div>
      <div>
        @if($user->sex == 1)
        <span>性別 : </span><span>男</span>
        @elseif($user->sex == 2)
        <span>性別 : </span><span>女</span>
        @else
        <span>性別 : </span><span>その他</span>
        @endif
      </div>
      <div>
        <span>生年月日 : </span><span>{{ $user->birth_day }}</span>
      </div>
      <div>
        @if($user->role == 1)
        <span>権限 : </span><span>教師(国語)</span>
        @elseif($user->role == 2)
        <span>権限 : </span><span>教師(数学)</span>
        @elseif($user->role == 3)
        <span>権限 : </span><span>講師(英語)</span>
        @else
        <span>権限 : </span><span>生徒</span>
        @endif
      </div>
      <div>
        @if($user->role == 4)
        <span>選択科目 :
        </span>
         @foreach($user->subjects as $subject)
        <span>{{ $subject->subject }}</span>
        @endforeach
        @endif
      </div>
    </div>
    @endforeach
  </div>

  <div class="search_area w-25 border">
    <div class="" style="padding-right:50px";>
      <div class="search-condition-part">
        <p style="font-size:20px;">検索</p>
        <input type="text" class="search-area-box free_word" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
      </div>
      <div class="search-condition-part">
        <label>カテゴリ</label><br>
        <select class="search-area-box area-box2" form="userSearchRequest" name="category">
          <option value="name">名前</option>
          <option value="id">社員ID</option>
        </select>
      </div>
      <div class="search-condition-part">
        <label>並び替え</label><br>
        <select class="search-area-box area-box2" name="updown" form="userSearchRequest">
          <option value="ASC">昇順</option>
          <option value="DESC">降順</option>
        </select>
      </div>
      <div class="search-condition-part">
        <p class="m-0 search_conditions"><span>検索条件の追加<span class="search_conditions_inner"></span></span></p>
        <div class="search_conditions_inner">
          <div class="search-condition-part">
            <label>性別</label><br>
            <span>男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
            <span>女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
            <span>その他</span><input type="radio" name="sex" value="3" form="userSearchRequest">
          </div>
          <div class="search-condition-part">
            <label>権限</label><br>
            <select class="search-area-box area-box3" name="role" form="userSearchRequest" class="engineer">
              <option selected disabled>----</option>
              <option value="1">教師(国語)</option>
              <option value="2">教師(数学)</option>
              <option value="3">教師(英語)</option>
              <option value="4" class="">生徒</option>
            </select>
          </div>
          <div class="search-condition-part selected_engineer">
            <label>選択科目</label>
            <div style="display:flex;">
            @foreach($subjects as $subject)
            <div class="select-subjects" style="margin-right:10px">
              <label>{{ $subject->subject }}</label>
              <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" form="userSearchRequest">
           </div>
           @endforeach
            </div>
            </div>

          </div>
        </div>
      </div>
      <div class="search-condition-button">
        <input type="submit" class="btn btn-info search_btn" name="search_btn" value="検索" form="userSearchRequest">
      </div>
       <div class="search-condition-button">
        <input type="reset" class="reset_btn" value="リセット" form="userSearchRequest">
      </div>
    </div>
    <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
  </div>
</div>
@endsection
