<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    private $status = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function findby_article(Int $article_id)
    {
        $like = new Like();
        $LikeCnt = $like->where('article_id', '=' , $article_id)->count();
 
        return response()->json($LikeCnt);
    }

    public function findby_article_user(Int $user_id ,Int $article_id)
    {
        $like = new Like();
        $Exist = $like->where([['user_id', '=' , $user_id],['article_id', '=' , $article_id]])->exists();
 
        return response()->json($like->where([['user_id', '=' , $user_id],['article_id', '=' , $article_id]])->exists());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function edit(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Int $user_id ,Int $article_id)
    {

        $message = null;
        $Like = new Like();

        if (empty($user_id) || empty($article_id)) {
            return response()->json(['status' => 400, 'message' => 'システムエラー']);
        }

        try {
            // トランザクションの開始
            \DB::beginTransaction();
            $ActiveFlg = false;
            $like = new Like();
            $Exist = $like->where([['user_id', '=' , $user_id],['article_id', '=' , $article_id]])->exists();

            if ($Exist == true) {
                $like->where([['user_id', '=' , $user_id],['article_id', '=' , $article_id]])->delete();
            } else {
                $Like->user_id = $user_id;
                $Like->article_id = $article_id;
        
                $Like->save();
                $ActiveFlg = true;
            }

            // 全ての保存処理が成功したので処理を確定する
            \DB::commit();

            return response()->json(['ActiveFlg' => $ActiveFlg ,'status' => $this->status, 'message' => '保存に成功しました。']);

        } catch (\Throwable $e) {
            // 例外が起きたらロールバックを行う
            \DB::rollback();

            // 失敗した原因をログに残す
            \Log::error($e);

            // フロントにエラーを通知
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function destroy(Like $like)
    {
        //
    }
}
