<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
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
    public function store(CommentRequest $request)
    {
        $status = 200;
        $message = null;

        $data = $request->all();
        $comment = new Comment();
        $article = new Article();

        $exists = $article->where('id', $data['article_id'])->exists();

        try {
            $exists = $article->where('id', $data['article_id'])->exists();
            try {
                // トランザクションの開始
                \DB::beginTransaction();
    
                $comment->article_id = $data['article_id'];
                $comment->user_id = $data['user_id'];
                $comment->title = $data['title'];
                $comment->content = $data['content'];
        
            //    $comment->save();
                $comment->saveOrFail();
    
                // 全ての保存処理が成功したので処理を確定する
                \DB::commit();
    
                return response()->json(['post' => $data, 'status' => $this->status, 'message' => '保存に成功しました。']);
    
            } catch (\Throwable $e) {
                // 例外が起きたらロールバックを行う
                \DB::rollback();
    
                // 失敗した原因をログに残す
                \Log::error($e);
    
                // フロントにエラーを通知
                throw $e;
            }

       } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') { // integrity constraint violation
                return response()->json(['post' => $data, 'status' => 23000, 'message' => 'データベースエラーが発生しました。']);
            //    return back()->withError('Invalid data');
            }
       }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($article_id)
    {   
        $comment = new Comment();
        return Comment::where('article_id', '=', $article_id)->orderBy('created_at', 'desc')->with('user:id,name')->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = 200;
        $message = null;

        try {
            // トランザクションの開始
            \DB::beginTransaction();

            Comment::destroy($id);

            // 全ての保存処理が成功したので処理を確定する
            \DB::commit();

            return response()->json(['status' => $this->status, 'message' => '削除に成功しました。']);

        } catch (\Throwable $e) {
            // 例外が起きたらロールバックを行う
            \DB::rollback();

            // 失敗した原因をログに残す
            \Log::error($e);

            // フロントにエラーを通知
            throw $e;
        }
    }
}
