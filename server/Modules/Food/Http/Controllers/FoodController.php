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
            $arr_video = array();
            $arr_image = array();
            $varKeyFile = ".file";
            if(isset($request->image)){
                foreach ($request->image as $key => $value) {
                    if($request->hasFile('image.'.$key . $varKeyFile))
                        {
                            $images = $request->file('image.'.$key . $varKeyFile);
                            $filename = "FDIM_".($key+1).$request->m_user_tabs_id
                                .$this->controller->generateCode()
                                ."."
                                .pathinfo($images->getClientOriginalName(), PATHINFO_EXTENSION);
                            $images->move(storage_path("images"), $filename);
                            array_push($arr_image, $filename);
                        }
                }
            } 
            if(isset($request->videos)){
                foreach ($request->videos as $key => $value) {
                    if($request->hasFile('videos.'.$key . $varKeyFile))
                        {
                            $videos = $request->file('videos.'.$key . $varKeyFile);
                            $filename = "FDVI_".($key+1).$request->m_user_tabs_id
                                .$this->controller->generateCode()
                                ."."
                                .pathinfo($videos->getClientOriginalName(), PATHINFO_EXTENSION);
                            $videos->move(storage_path("videos"), $filename);
                            array_push($arr_video, $filename);
                        }
                }
            }

            $request["video"] = $arr_video;
            $request["images"] = $arr_image;
            $food = $this->food->create($request->all());

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
        //
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
