<?php

namespace Modules\Food\Http\Controllers;

use App\Http\Controllers\Controller as ControllersController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Approval\Entities\TApprovalTransactionFoodTabs;
use Modules\Food\Entities\TFoodAttachmentTab;
use Modules\Food\Entities\TFoodTab;
use Modules\User\Entities\MUserTab;

class FoodController extends Controller
{

    protected $user;
    protected $food;
    protected $transaction;
    protected $menu;
    protected $tFoodAttachmentTab;
    protected $controller;

    public function __construct(
        MUserTab $user, 
        TFoodTab $food, 
        TApprovalTransactionFoodTabs $transaction,
        TFoodAttachmentTab $tFoodAttachmentTab,
        ControllersController $controller
    ) {
        $this->user = $user;
        $this->food = $food;
        $this->transaction = $transaction;
        $this->controller = $controller;
        $this->tFoodAttachmentTab = $tFoodAttachmentTab;
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
            },'attachment'])
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
            $food = $this->food->create($request->all());
            $this->transaction->create([
                't_food_tabs_id' => $food->id,
                'm_status_tabs_id' => 1,
                'responded_by' => $request->m_user_tabs_id,
                'responded_at' => now(),
            ]);
            $this->addAttachment($food,$request);

            DB::commit();
            return $this->controller->responses("FOOD CREATED",200, $food);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->controller->responses("FAILURE REQUEST",500,$th->getMessage());
        }
    }

    public function addAttachment(TFoodTab $food, Request $request){
        $keyFile = '.file';
        if(isset($request->image) && count($request->image) > 0){
            $arrFile = array();
            foreach ($request->image as $key => $value) {
                if($request->hasFile($this->controller->pathImage.'.'.$key . $keyFile))
                {
                    $files = $request->file($this->controller->pathImage.'.'.$key . $keyFile);
                    $size = $files->getSize() / 1000;
                    $filename = $request->m_user_tabs_id
                        .$this->controller->generateCode()
                        ."."
                        .pathinfo($files->getClientOriginalName(), PATHINFO_EXTENSION);
                    $files->move(storage_path($this->controller->pathImage), $filename);
                    array_push(
                        $arrFile,
                        [
                            't_food_tabs_id' => $food->id,
                            "filename" => $filename,
                            "size" => $size,
                            "type" => 1,
                        ]
                    );
                }
            }
            $food->attachment()->createMany($arrFile);
        }

        if(isset($request->video) && count($request->video) > 0){
            $arrFile = array();
            foreach ($request->video as $key => $value) {
                if($request->hasFile($this->controller->pathVideo.'.'.$key . $keyFile))
                {
                    $files = $request->file($this->controller->pathVideo.'.'.$key . $keyFile);
                    $size = $files->getSize() / 1000;
                    $filename = $request->m_user_tabs_id
                        .$this->controller->generateCode()
                        ."."
                        .pathinfo($files->getClientOriginalName(), PATHINFO_EXTENSION);
                    $files->move(storage_path($this->controller->pathVideo), $filename);
                    array_push(
                        $arrFile,
                        [
                            't_food_tabs_id' => $food->id,
                            "filename" => $filename,
                            "size" => $size,
                            "type" => 2,
                        ]
                    );
                }
            }
            $food->attachment()->createMany($arrFile);
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
            $food->update($request->all());
            $this->addAttachment($food,$request);
            DB::commit();
            return $this->controller->responses("FOOD UPDATED",200, $food);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->controller->responses("FAILURE UPDATED", 500, $th->getMessage());
        }
    }

    public function destroyFile($id){
        try {
            DB::beginTransaction();
            $attachment = $this->tFoodAttachmentTab->find($id);
            $attachment->delete();
            unlink(storage_path(
                $attachment->type == 1 ? $this->controller->pathImage : $this->controller->pathVideo
                .'/'
                .$attachment->filename));
            DB::commit();
            return $this->controller->responses("SUCCESS DESTROY", 200, $attachment);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->controller->responses("FAILURE DESTROY", 500, $th->getMessage());
        }
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
