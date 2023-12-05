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
        return view('food::index');
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
            'm_user_tabs_id' => 'required|unique:m_user_tabs',
            'price' => 'required',
            'shop' => 'required',
        ])){
            return $valid;
        }

        try {
            DB::beginTransaction();
            if(isset($request->image)){
                foreach ($request->image as $key => $value) {
                    if($value->hasFile("image"))
                        {
                            $images = $value->file("image");
                            $filename = "FDIM_".($key+1).$request->m_user_tabs_id . $images->getClientOriginalName();
                            $images->move(public_path("images"), $filename);
                        }
                }
            }
            
            DB::commit();
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
        return view('food::show');
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
        //
    }
}
