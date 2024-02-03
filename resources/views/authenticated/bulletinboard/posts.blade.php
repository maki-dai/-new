@extends('layouts.sidebar')

@section('content')
<div class="board_area w-100 h-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5 mb-5">
    <!-- <p class="w-75 m-auto">投稿一覧</p> -->
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <b>
        <p class="post-username"><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p><a  class="post-title" href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      </b>
      <div class="post_bottom_area d-flex">
        <div class="d-flex post_status">
        <!-- サブカテゴリ表示 -->
        <div class="">
            @foreach($post->subCategories as $sub_category)
            <p class="post-subcategory">{{ $sub_category->sub_category }}</p>
            @endforeach
        </div>
        <div class="like-comment-counts">
          <div class="mr-5">
            <i class="fa fa-comment"></i><span class="comment_counts{{ $post->id }}">{{ $post_comment->commentCounts($post->id) }}</span>
          </div>
          <div>
            @if(Auth::user()->is_Like($post->id))
            <p class="m-0"><i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $like->likeCounts($post->id) }}</span></p>
            @else
            <p class="m-0"><i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $like->likeCounts($post->id) }}</span></p>
            @endif
          </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="other_area w-25" style="margin-right: 5%;">
    <div class=" mt-5">
      <div class="post-side post-btn"><a href="{{ route('post.input') }}" class="btn btn-info post_btn w-100">投稿</a></div>
      <div class="post-side search-area">
        <input type="text" style="width:75%; border-radius: 8px 0% 0% 8px;
    border: solid thin #cccccc;background:transparent;
"placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
        <input type="submit" style="width:25%; border-radius: 0 8px 8px 0;" value="検索" form="postSearchRequest" class="btn btn-info">
      </div>
      <div class="post-side color-btn">
      <input type="submit" name="like_posts" class="btn btn-pink category_btn" value="いいねした投稿" form="postSearchRequest">
      <input type="submit" name="my_posts" class="btn btn-yellow btn category_btn" value="自分の投稿" form="postSearchRequest">
      </div>

      <div class="category-search-area">
         <p style="font-size: 14px;">カテゴリ検索</p>
        <div class="category-search">


             @foreach($main_categories as $main_category)
             <div class="main-category-box">
                  <p class="category_conditions category"><span>{{ $main_category->main_category }}</span></p>
                      <!-- メインカテゴリと一致するサブカテゴリを表示 -->

               <ul class="sub-category category">
                  @foreach($sub_categories as $sub_category)
                       @if($main_category->id  == $sub_category->main_category_id)

                    <li><input type="submit" name="category_word" class="sub_category_btn" value="{{$sub_category->sub_category}}" form="postSearchRequest">
                    </li>

                  @endif

               @endforeach
              </ul>
                 </div>
            @endforeach


        </div>
      </div>
    </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
@endsection


 <!-- <select class="w-100" form="postCreate" name="post_category_id">
     @foreach($main_categories as $main_category)
        <optgroup label="{{ $main_category->main_category }}">

メインカテゴリと一致するサブカテゴリを表示 -->
       <!-- @foreach($sub_categories as $sub_category)
         @if($sub_category->main_category_id == $main_category->id)
          <option value="{{ $sub_category->id }}">{{$sub_category->sub_category}}</option>
          @endif
       @endforeach

        </optgroup>

    @endforeach
      </select> -->
