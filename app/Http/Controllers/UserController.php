<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\MasterRepository;
use App\Repositories\AuthRepository;
use App\Http\Requests\UserRequest;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Lang;


class UserController extends Controller
{
    protected $repUser;
    protected $repMaster;
    protected $repAuth;
    protected $contractStatus;
    protected $services;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(
        UserRepository $user,
        MasterRepository $master,
        AuthRepository $auth
    )
    {
        $this->repUser      = $user;
        $this->repMaster    = $master;
        $this->repAuth      = $auth;
        $this->middleware('auth');
        $this->middleware('authority', ['except' => ['accountEdit', 'accountUpdate']]);

        //get master status
        $contracts          = $this->repMaster->getAllByField('group', config('constants.master.contract_status'));
        $master_label       = 'name_'.Lang::locale();
        foreach ($contracts as $contract) {
            $this->contractStatus[$contract->code] = $contract->$master_label;
        }
        //get all services
        $contracts          = $this->repMaster->getAllByField('group', config('constants.master.services'));
        foreach ($contracts as $contract) {
            $this->services[$contract->code] = $contract->$master_label;
        }
    }

    public function index(Request $request)
    {
        $user_id        = Auth::user()->id;
        $keyword        = $request->get('keyword','');
        $perPage        = config('constants.per_page');
        $users          = $this->repUser->getAll($keyword, $perPage[3]);
        $usersAccess    = [];
        foreach ($users as $user) {
            $auths      = $user->auth->toArray();
            foreach ($auths as $auth) {
                if($auth['access_token']
                    && isset($this->services[$auth['service_code']])
                    && (!isset($usersAccess[$user->id]) || !in_array($this->services[$auth['service_code']], $usersAccess[$user->id]))
                ) {
                    $usersAccess[$user->id][] = $this->services[$auth['service_code']];
                }
            }
        }
        return view('user.index')->with([
            'users'             => $users,
            'user_id'           => $user_id,
            'contract_status'   => $this->contractStatus,
            'usersAccess'       => $usersAccess,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = $this->defineUserGroup();

        return view('user.create')->with([
            'users'             => null,
            'group'             => $group,
            'contract_status'   => $this->contractStatus,
        ]);
    }

    private function defineUserGroup(){
        $group = config('constants.authority');
        foreach($group as $key => $value){
            if(Lang::has('select.'.$key)){
                $group[trans('select.'.$key)] = $value;
                unset($group[$key]);
            }
        }
        $group = array_flip($group);
        return $group;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $inputs = $request->all();
        DB::beginTransaction();
        try{
            $this->repUser->store($inputs);
            DB::commit();
            return redirect('user')->with('alert-success', trans('message.save_success', ['name' => trans('default.user')]));
        } catch(\Exception $e){
            DB::rollback();
            return redirect()->back()->with('alert-danger', trans('message.save_error', ['name' => trans('default.user')]));
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
        $user               = $this->repUser->getById($id);
        $group              = $this->defineUserGroup();
        if($user){
            return view('user.create')->with([
                'user'              => $user,
                'group'             => $group,
                'contract_status'   => $this->contractStatus,
            ]);
        }
        return redirect('user')->with('alert-danger', trans('message.exiting_error', ['name' => trans('default.user')]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $inputs = $request->all();
        if(empty($inputs['password'])){
            unset($inputs['password']);
        }
        DB::beginTransaction();
        try{
            $user = $this->repUser->getById($id);
            if($user){
                $this->repUser->update($user, $inputs);
                DB::commit();
                return redirect('user')->with('alert-success', trans('message.update_success', ['name' => trans('default.user')]));
            }
            return redirect()->back()->with('alert-danger', trans('message.update_error', ['name' => trans('default.user')]));
        } catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('alert-danger', trans('message.update_error', ['name' => trans('default.user')]));
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
        $user_id = Auth::user()->id;
        $user = $this->repUser->getById($id);
        if($user && $user_id != $id){
            $this->repUser->destroy($id);
            return Response::json(array('success' => true), 200);
        }
        $errors['msg'] = trans("message.common_error");
        return Response::json(array(
            'success' => false,
            'errors' => $errors
        ), 400);
    }

    /**
     * Edit my account with header link and left menu
     */
    public function accountEdit()
    {
        $user = Auth::user();
        return view('user.my_edit')->with([
            'user'              => $user,
            'contract_status'   => $this->contractStatus,
        ]);
    }

    /**
     * Update my account with header link and left menu
     */
    public function accountUpdate(UserRequest $request)
    {
        $user = Auth::user();
        $inputs = $request->all();
        if(!isset($inputs['password'])){
            unset($inputs['password']);
        }
        if(!isset($inputs['contract_status'])){
            unset($inputs['contract_status']);
        }
        DB::beginTransaction();
        try{
            $this->repUser->updateAccount($user, $inputs);
            DB::commit();
            return redirect()->back()->with('alert-success', trans('message.update_success', ['name' => trans('default.profile')]));
        } catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('alert-danger', trans('message.update_error', ['name' => trans('default.profile')]));
        }
    }

}
