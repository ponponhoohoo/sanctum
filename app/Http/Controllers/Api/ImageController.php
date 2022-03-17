<?php

namespace App\Http\Controllers\Api;

use App\Models\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    public function DeleteByUser($user_id)
    {
        $status = 200;
        $message = null;

        try {
            // トランザクションの開始
            \DB::beginTransaction();
            
            $images = Image::where('user_id', $user_id)->get();
            foreach ($images as $image) {
                //ローカル
                if (app()->isLocal()) {

                    //前の画像を削除
                    if (Storage::exists('public/upload/'. $image->path)) {
                        Storage::disk('public')->delete('upload/' . $image->path);
                    }
                }
                //本番環境
                if (app()->isProduction()) {
                    //前の画像を削除
                    $PreFileName = $image->path;
                    if ($PreFileName != "" && !empty($PreFileName)) {
                        $S3DelFile = basename($PreFileName);
                        $disk = Storage::disk('s3');
                        $disk->delete('upload/' . $S3DelFile);
                    }
                }
            }

            Image::where('user_id', $user_id)->delete();

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
