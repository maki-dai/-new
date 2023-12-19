<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use App\Http\Requests\BulletinBoard\PostEditFormRequest;
use App\Http\Requests\BulletinBoard\PostCommentFormRequest;
use App\Http\Requests\BulletinBoard\MainCategoryFormRequest;
use App\Http\Requests\BulletinBoard\SubCategoryFormRequest;
use Auth;


class PostsController extends Controller
{
    public function show(Request $request){
        // サブカテゴリも取得できるように記述変更必要↓
        $posts = Post::with('user','postComments','subCategories')->get();

        $main_categories = MainCategory::get();
        $sub_categories = SubCategory::with('mainCategory')->get();

        // $post_id = $posts->id;
        // // $post_sub_category =SubCategory::find($post_id);
        // $post_sub_categories = Post::with('subCategories')
        //      ->whereHas('subCategories', function($q) use ($post_id){
        //         $q->where('post_sub_categories.post_id',$post_id);
        //     })->get();
        $like = new Like;
        $post_comment = new Post;

        if(!empty($request->keyword)){

            $sub_search_word =$request->keyword;
            $sub_category_word = Subcategory::where('sub_category',$sub_search_word)->pluck('sub_category');
            $sub_search_id = SubCategory::where('sub_category',$sub_search_word)->pluck('id');

            $posts = Post::with('user', 'postComments','subCategories')
            ->where('post_title', 'like', '%'.$request->keyword.'%')
            ->orWhere('post', 'like', '%'.$request->keyword.'%')
            ->get();

            // →ここだけ！！$sub_category_wordに入らない！直接指定すると問題ない
            if($sub_search_word == $sub_category_word){
            $posts = Post::with('user', 'postComments','subCategories')
            ->orWhereHas('subCategories', function($q) use ($sub_search_id){
                $q->where('post_sub_categories.sub_category_id',$sub_search_id);
            })->get();
            // ->get();
            // if文で一致するときのみが必要そう！
            }
// dd($sub_search_word,$sub_category_word);
             //サブカテゴリ完全一致検索記述 機能はOK
            // (検索ワードがサブカテゴリと完全一致のとき、サブカテゴリテーブルから一致するレコードのidを取得)
            // （取得したidをwhereHasで中間テーブルから一致する情報抽出→Postからゲット）


        // サブカテゴリー選んだら同じサブカテゴリに属してるものだけ抽出
        }else if($request->category_word){
             $sub_category = $request->category_word;
             $sub_category_id = SubCategory::where('sub_category',$sub_category)->pluck('id');
             $posts = Post::with('subCategories')
             ->whereHas('subCategories', function($q) use ($sub_category_id){
                $q->where('post_sub_categories.sub_category_id',$sub_category_id);
            })->get();
// dd($sub_category_id);
             //  $sub_category_posts = Post::with('subCategory')->whereIn('id',$sub_category_id)->get();
            // $posts = Post::with('user', 'postComments')->get();

        }else if($request->like_posts){
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'postComments')
            ->whereIn('id', $likes)->get();
        }else if($request->my_posts){
            $posts = Post::with('user', 'postComments')
            ->where('user_id', Auth::id())->get();
        }
        return view('authenticated.bulletinboard.posts', compact('posts', 'main_categories','sub_categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id){
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        $main_categories = MainCategory::get();
        $sub_categories = SubCategory::with('mainCategory')->get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories','sub_categories'));
    }


    public function postCreate(PostFormRequest $request){

        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        $sub_category_id = $request->post_category_id;

        $sub_category = SubCategory::find($sub_category_id);
        $sub_category->posts()->attach($post->id);

         // postと紐づくsubcategoryにid入れてリレーション成立へ？
        // post_sub_categories 中間テーブルのsub_category_idにインプットしたい
        // $sub_categories->post_id = $post->id;
        // $sub_categories = subCategories()->attach($post_category_id);

        return redirect()->route('post.show');
    }

    // ここのバリデーションが課題　編集
    public function postEdit(PostEditFormRequest $request){
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }

    // メインカテゴリ
    public function mainCategoryCreate(MainCategoryFormRequest $request){
        MainCategory::create(['main_category' => $request->main_category]);
        return redirect()->route('post.input');
    }
    // サブカテゴリ
     public function subCategoryCreate(SubCategoryFormRequest $request){
        SubCategory::create([
            'main_category_id' => $request->main_category_id,
            'sub_category' =>  $request->sub_category
        ]);
        return redirect()->route('post.input');
    }


    // コメントバリデーション　　確認！
    public function commentCreate(PostCommentFormRequest $request){
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard(){
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

        return response()->json();
    }
}
