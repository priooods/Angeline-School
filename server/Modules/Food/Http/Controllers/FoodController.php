<?php

namespace Modules\Food\Http\Controllers;

use App\Http\Controllers\Controller as ControllersController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Approval\Entities\TApprovalTransactionFoodTabs;
use Modules\Food\Entities\TFoodTab;
use Modules\User\Entities\MUserTab;

class FoodController extends Controller
{

    protected $user;
    protected $food;
    protected $transaction;
    protected $menu;
    protected $controller;

    public function __construct(
        MUserTab $user, 
        TFoodTab $food, 
        TApprovalTransactionFoodTabs $transaction,
        ControllersController $controller
    ) {
        $this->user = $user;
        $this->food = $food;
        $this->transaction = $transaction;
        $this->controller = $controller;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $this->controller->responses("ALL FOOD", 200, 
            $this->food
            ->with(['approval' => function($a){
                $a->with('status');
            }])
            ->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('food::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if($valid = $this->controller->validating($request,[
            'description' => 'required',
            'm_user_tabs_id' => 'required',
            'price' => 'required|max:8',
            'shop' => 'required|max:100',
        ])){
            return $valid;
        }

        try {
            DB::beginTransaction();
            if(isset($request->image) && count($request->image) > 0){
                $arr_image = $this->storageFileFood(
                    $request->image,
                    $this->controller->pathImage,
                    "FDIM",
                    "file",
                    $request);
            }

            if(isset($request->video) && count($request->video) > 0){
                $arr_video = $this->storageFileFood(
                    $request->video,
                    $this->controller->pathVideo,
                    "FDVI",
                    "file",
                    $request);
            }

            $request->image = $arr_image ?? null;
            $request->video = $arr_video ?? null;
            $food = $this->food->create([
                "m_user_tabs_id" => $request->m_user_tabs_id,
                "description" => $request->description,
                "price" => $request->price,
                "shop" => $request->shop,
                "latitude" => $request->latitude ?? null,
                "longitude" => $request->longitude ?? null,
                "video" => $request->video,
                "image" => $request->image,
            ]);

            $this->transaction->create([
                't_food_tabs_id' => $food->id,
                'm_status_tabs_id' => 1,
                'responded_by' => $request->m_user_tabs_id,
                'responded_at' => now(),
            ]);

            DB::commit();
            return $this->controller->responses("FOOD CREATED",200, $food);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->controller->responses("FAILURE REQUEST",500,$th->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return $this->controller->responses("MY UPLOADED", 200, 
            $this->food->where('m_user_tabs_id', $id)
            ->with(['approval' => function($a){
                $a->with('status');
            }])
            ->get());
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('food::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if($valid = $this->controller->validating($request,[
            'description' => 'required',
            'price' => 'required|max:8',
            'shop' => 'required|max:100',
        ])){
            return $valid;
        }

        try {
            DB::beginTransaction();
            $food = $this->food->where('id',$id)->first();
            if(isset($request->image) && count($request->image) > 0){
                $arr_image = $this->storageFileFood(
                    $request->image,
                    $this->controller->pathImage,
                    "FDIM",
                    "file",
                    $request);
                $request->images = $arr_image;
                foreach ($food->images as $value) {
                    unlink(storage_path($this->controller->pathImage.'/'.$value));
                }
                $food->update([
                    "images" => null
                ]);
            }
            if(isset($request->video) && count($request->video) > 0){
                $arr_video = $this->storageFileFood($request->videos,
                $this->controller->pathImage,"FDVI","file",$request);
                $request["videos"] = $arr_video;
                foreach ($food->images as $value) {
                    unlink(storage_path($this->controller->pathImage.'/'.$value));
                }
                $food->update([
                    "videos" => null
                ]);
            }
            $food->update($request->all());
            DB::commit();
            return $this->controller->responses("FOOD UPDATED",200, $food);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->controller->responses("FAILURE UPDATED", 500, $th->getMessage());
        }
    }

    public function storageFileFood(
        $arrays,
        string $requestKey,
        string $codeNameFile, 
        string $keyFile,
        Request $request)
    {
        $arrFile = array();
        foreach ($arrays as $key => $value) {
            if($request->hasFile($requestKey.'.'.$key . '.' . $keyFile))
            {
                $images = $request->file($requestKey.'.'.$key . '.' . $keyFile);
                $filename = $codeNameFile."_".($key+1).$request->m_user_tabs_id
                    .$this->controller->generateCode()
                    ."."
                    .pathinfo($images->getClientOriginalName(), PATHINFO_EXTENSION);
                $images->move(storage_path($requestKey), $filename);
                array_push($arrFile, $filename);
            }
        }
        if(count($arrFile) < 1) {
            return $arrFile = null;
        }
        return $arrFile;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            if(!$data = $this->food->find($id)){
                return $this->controller->responses("FAILURE REMOVE ITEM",200,
                "Content yang anda cari tidak ditemukan");
            }
            $data->delete();
            DB::commit();
            return $this->controller->responses("SUCCESS REMOVE ITEM",200,
                "Content berhasil di hapus !");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->controller->responses("FAILURE REQUEST",500,$th->getMessage());
        }
    }
}
