<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{

    private $status = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return Article::with('comment','comment.user:id,name','user:id,name','category','image')->orderBy('created_at', 'desc')->get();
    }

    public function search(Request $request)
    {

        $message = null;

        $article = Article::query()->orderBy('created_at', 'desc');

    //    $query->where('emergency','=', $request->emergency);
        if(isset($request->content)){
            $article->Where('content','like', "%".$request->content."%");
         //   ->Where('category', '=', 1)
        }
        if(isset($request->category)){
            $article->Where('category','=', $request->category);
         //   ->Where('category', '=', 1)
        }

        $data = $article->with('comment','comment.user:id,name','user:id,name','category','image')->get();
      
        return response()->json(['post' => $data, 'status' =>  $this->status, 'message' => '検索に成功しました。']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {

        $status = 200;
        $message = null;

        $data = $request->all();
        $article = new Article();

        try {
            // トランザクションの開始
            \DB::beginTransaction();

            if ($file = $request->path) {
                if (app()->isLocal()) {
                    $File_Original = time() . $file->getClientOriginalName();
                    $Unique_Id  = uniqid();
                    $FileName = $Unique_Id . '_' . $File_Original;
                    $img = $request->path->storeAs('public/upload',$FileName);
                    
                }
                if (app()->isProduction()) {
                    // 本番環境の場合の処理
                    $path = Storage::disk('s3')->putFile('upload', $file);
                    $FileName = Storage::disk('s3')->url($path);
                }
            } 
    
            $article->user_id = $data['id'];
            $article->title = $data['title'];
            $article->content = $data['content'];
            $article->category = $data['category'];
    
            $article->save();
    
            //保存した記事のID取得
            $new_article = Article::orderBy('id', 'desc')->first();
            $post = Article::find($new_article->id);
    
            $image = new Image(['user_id' => $data['id'],'path' => $FileName]);
            $new = $post->image()->save($image);
    
            $post->image_id = $new->id;
            $post->saveOrFail();

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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        // $Article = Article::where('id', $id)->with('comment','comment.user:id,name','user:id,name','category','image')->get();
        // $Article = $Article->sortBy('comment.created_at')->values();;
        // return $Article;
        return Article::with('comment','comment.user:id,name','user:id,name','category','image')->find($id);
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
    public function update(ArticleRequest $request, $id)
    {
        $status = 200;
        $message = null;
        $data = $request->all();

        $article = Article::find($id);

        try {
            // トランザクションの開始
            \DB::beginTransaction();

            if ($file = $request->path) {
                //ローカル
                if (app()->isLocal()) {
                    $File_Original = time() . $file->getClientOriginalName();
                    $Unique_Id  = uniqid();
                    $FileName = $Unique_Id . '_' . $File_Original;
                    $img = $request->path->storeAs('public/upload',$FileName);

                    //前の画像を削除
                    $PreFileName = $data['path_original'];
                    if (Storage::exists('public/upload/'. $PreFileName)) {
                        Storage::disk('public')->delete('upload/' . $PreFileName);
                    }
                }
                //本番環境
                if (app()->isProduction()) {
                    $path = Storage::disk('s3')->putFile('upload', $file);
                    $FileName = Storage::disk('s3')->url($path);

                    //前の画像を削除
                    $PreFileName = $data['path_original'];
                    if ($PreFileName != "" && !empty($PreFileName)) {
                        $S3DelFile = basename($PreFileName);
                        $disk = Storage::disk('s3');
                        $disk->delete('upload/' . $S3DelFile);
                    }
                }

                // //image DB更新
                $image = Image::where([
                    ['user_id', '=', $data['user_id']],
                    ['article_id', '=',$id],
                  ]
                )->first();
                $image->path = $FileName;
                $image->save();

            }

            $article->title = $data['title'];
            $article->content = $data['content'];
            $article->category = $data['category'];

            $article->save();

            // 全ての保存処理が成功したので処理を確定する
            \DB::commit();

            return response()->json(['status' => $this->status, 'message' => '更新に成功しました。']);

        } catch (\Throwable $e) {
            // 例外が起きたらロールバックを行う
            \DB::rollback();

            // 失敗した原因をログに残す
            \Log::error($e);

            // フロントにエラーを通知
            throw $e;
        }

        return response()->json(['post' => $data, 'status' => $this->status, 'message' => $message]);
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

            $Article = Article::with('image')->find($id);

            if (!empty($Article->image->path)) {
                if (Storage::exists('public/upload/'. $Article->image->path)) {
                    Storage::disk('public')->delete('upload/' . $Article->image->path);
                 }
            }
           
            Article::destroy($id);

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
