<?php

namespace App\Http\Controllers;

use App\Enums\BaseLimit;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserPasswordAdminRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->getUsers(
            $request->input('perPage', BaseLimit::LIMIT_10),
            $request->except('perPage')
        );

        return response()->json([
            'status' => true,
            'message' => 'All Users Get Successfully',
            'data' => $users,
        ], Response::HTTP_OK);
    }

    public function store(CreateUserRequest $request)
    {
        $user = $this->userService->createUser($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Users Create Successfully',
            'data' => $user,
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $user = $this->userService->getById($id);

        return response()->json([
            'status' => true,
            'message' => 'User Get Successfully',
            'data' => $user,
        ], Response::HTTP_OK);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = $this->userService->updateUser($id, $request->all());

            return response()->json([
                'status' => true,
                'message' => 'User Updated Successfully',
                'data' => $user,
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'status' => false,
                    'message' => 'The email has already been taken.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating user.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePassword(UpdateUserPasswordAdminRequest $request)
    {
        $user = $this->userService->updateUserPassword($request);

        return response()->json([
            'status' => true,
            'message' => 'User Updated Successfully',
            'data' => $user,
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->userService->delete($id);

        return response()->json([
            'status' => true,
            'message' => 'User Deleted Successfully',
        ], Response::HTTP_OK);
    }
}
