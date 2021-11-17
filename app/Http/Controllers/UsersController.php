<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        Log::channel('attention')->info('Get all users',[
            'listOfId' => User::first()
        ]);

        $users = User::orderBy('id','DESC')->with('orders')->get();
        return response()->json($users->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email:rfc,dns|unique:users',
            'number_of_orders' => 'required|between:0,99.99'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->number_of_orders = $request->number_of_orders;

        Log::channel('user')->info('User created',[
            'userCreated' => User::first()
        ]);

        if ($user->save()) {
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'New user created'
            ]);


        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create user'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        return User::where('id',$id)->with('orders')->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return User
     */
    public function update(Request $request, int $id): User
    {
        $user = User::find($id);
        $user->update($request->all());

        Log::channel('user')->info('User updated',[
            'userUpdated' => User::find($id)
        ]);
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);

        Log::channel('user')->info('User deleted',[
            'userDeleted' => User::find($id)
        ]);

        if ($user->delete()) {
            return response()->json([
                'status' => true,
                'users' => $user,
                'message' => "User deleted correctly"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Can't delete this user"
            ]);
        }
    }
}
