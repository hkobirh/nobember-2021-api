<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $user = User::select('id', 'name', 'email')->get();
        return $user;
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
     * Internal server error.
     *
     * @param Request $request
     * @return JsonResponse|void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|max:25|min:3',
            'email'    => 'required|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) return error_validation($validator->errors());
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return success_message($user->only('name','email'),__('message.user.create.success'),201);

        } catch (Exception $e) {
            return error_message(__('message.user.create.error'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $user = User::select('id','name','email')->first($id);
        return $user ? success_message($user->only('name','email')):error_message(__('message.user.manage.not_found'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $data = User::find($id);

        $validator = Validator::make($request->all(), [
            'name'     => 'required|max:25|min:3',
            'email'    => 'required|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) return error_message($validator->errors());

        try {

            $data->name = $request->name;
            $data->email = $request->email;
            $data->password = Hash::make($request->password);
            $data->update();

            return success_message(__('message.user.update.success'));

        } catch (Exception $e) {
            return error_message(__('message.user.update.error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id):JsonResponse
    {
        $data = User::find($id);
        $data->delete();
        return success_message('','Data has been deleted.');
    }
}
