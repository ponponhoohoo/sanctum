<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TagnameRequest;
use App\Models\Tagname;

class TagnameController extends Controller
{

    private $status = 200;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tagname::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagnameRequest $request)
    {
        $message = null;

        $data = $request->all();
        $Tagname = new Tagname();

        try {
            try {
                // トランザクションの開始
                \DB::beginTransaction();
    
                $Tagname->name = $data['name'];
        
            //    $comment->save();
                $Tagname->saveOrFail();
    
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
    public function show($id)
    {
        return Tagname::find($id);
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
        $message = null;
        
        try {
            // トランザクションの開始
            \DB::beginTransaction();
           
            Tagname::destroy($id);

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
