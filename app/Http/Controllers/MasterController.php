<?php

namespace App\Http\Controllers;

use App\Repositories\MasterRepository;
use App\Http\Requests\MasterRequest;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Lang;


class MasterController extends Controller
{
    protected $repMaster;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(MasterRepository $master)
    {
        $this->repMaster  = $master;
        $this->middleware(['auth', 'authority']);
    }

    public function index(Request $request)
    {
        $keyword = $request->get('keyword','');
        $perPage = config('constants.per_page');
        $masters = $this->repMaster->getAll($keyword, $perPage[3]);
        return view('master.index')->with(['masters' => $masters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.create')->with(['master' => null]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MasterRequest $request)
    {
        $inputs = $request->all();
        DB::beginTransaction();
        try{
            $this->repMaster->store($inputs);
            DB::commit();
            return redirect('master')->with('alert-success', trans('message.save_success', ['name' => trans('default.master')]));
        } catch(\Exception $e){
            DB::rollback();
            return redirect()->back()->with('alert-danger', trans('message.save_error', ['name' => trans('default.master')]));
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $master = $this->repMaster->getById($id);
        if($master){
            return view('master.create')->with([
                'master' => $master
            ]);
        }
        return redirect('master')->with('alert-danger', trans('message.exiting_error', ['name' => trans('default.master')]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MasterRequest $request, $id)
    {
        $inputs = $request->all();
        DB::beginTransaction();
        try{
            $master = $this->repMaster->getById($id);
            $this->repMaster->update($master, $inputs);
            DB::commit();
            return redirect('master')->with('alert-success', trans('message.update_success', ['name' => trans('default.master')]));
        } catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('alert-danger', trans('message.update_error', ['name' => trans('default.master')]));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $master = $this->repMaster->getById($id);
        if($master){
            $this->repMaster->destroy($id);
            return Response::json(array('success' => true), 200);
        }
        $errors['msg'] = trans("message.common_error");
        return Response::json(array(
            'success' => false,
            'errors' => $errors
        ), 400);
    }
}
