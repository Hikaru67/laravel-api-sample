<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 *  @OA\Tag(
 *      name="User",
 *      description="User Resource",
 * )
 *
 *  @OA\Schema(
 *      schema="user",
 *      @OA\Property(
 *          property="name",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="email",
 *          type="number",
 *          example=1,
 *      ),
 *  )
 *
 *  @OA\Schema(
 *      schema="auth",
 *      @OA\Property(
 *          property="email",
 *          type="string",
 *          example="admin@admin.com",
 *      ),
 *      @OA\Property(
 *          property="password",
 *          type="string",
 *          example="123456",
 *      ),
 *  )
 *
 *  @OA\Schema(
 *      schema="userWithPassword",
 *      allOf={
 *          @OA\Schema(
 *              @OA\Property(
 *                  property="name",
 *                  type="string",
 *                  example="admin",
 *              ),
 *          ),
 *          @OA\Schema(
 *              @OA\Property(
 *                  property="old_password",
 *                  type="string",
 *                  example="123456",
 *              ),
 *          ),
 *          @OA\Schema(
 *              @OA\Property(
 *                  property="new_password",
 *                  type="string",
 *                  example="123456",
 *              ),
 *          ),
 *      }
 *  )
 */
class UserController extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->http = new Client([
            'base_uri' => config('app.url'),
            'verify' => false,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/user",
     *      tags={"User"},
     *      operationId="indexUser",
     *      summary="List User",
     *      @OA\Parameter(ref="#/components/parameters/page"),
     *      @OA\Parameter(ref="#/components/parameters/limit"),
     *      @OA\Parameter(ref="#/components/parameters/sortField"),
     *      @OA\Parameter(ref="#/components/parameters/sortOrder"),
     *      @OA\Response(
     *          response=200,
     *          description="Listed",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/user")
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->list($request->all(), ['roles']);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest $request
     * @return  \Illuminate\Http\Response
     *
     * @param  Request $request
     * @return  Response
     *
     *  @OA\Post(
     *      path="/api/user",
     *      tags={"User"},
     *      operationId="storeUser",
     *      summary="Create User",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/user"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Created",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/user",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function store(UserRequest $request)
    {
        $data = $request->only('name', 'email');
        $data['password'] = bcrypt($request->password);

        $user = $this->userRepository->create($data);

        if ($request->has('roles')) {
            $this->userRepository->syncRoles($user, $request->roles);
        }

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/user/{id}",
     *      tags={"User"},
     *      operationId="showUser",
     *      summary="Get User",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Getted",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/user",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function show(User $user)
    {
        return new UserResource($user->load('roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest $request
     * @param  \App\Models\User  $user
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Put(
     *      path="/api/user/{id}",
     *      tags={"User"},
     *      operationId="updateUser",
     *      summary="Update User",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *          ),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/user"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Updated",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/user",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->only('name', 'email');

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $this->userRepository->update($user, $data);

        if ($request->has('roles')) {
            $this->userRepository->syncRoles($user, $request->roles);
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Delete(
     *      path="/api/user/{id}",
     *      tags={"User"},
     *      operationId="deleteUser",
     *      summary="Delete User",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Deleted",
     *      ),
     *  )
     */
    public function destroy(User $user)
    {
        if ($user->id == auth()->guard('api')->id()) {
            abort(403, 'Access denied');
        }

        $this->userRepository->delete($user);

        return response()->json(null, 204);
    }

    /**
     * Login user.
     *
     * @param AuthRequest $request
     * @return UserResource
     *
     *  @OA\Post(
     *      path="/api/login",
     *      tags={"User"},
     *      operationId="loginUser",
     *      summary="Login User",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/auth"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Logged in",
     *      ),
     *  )
     */
    public function login(AuthRequest $request)
    {
        if (!config('setting.refresh_token')) {
            if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                abort(401, 'Email/Password do not match');
            }

            $user = auth()->user();
            $token = $user->createToken($user->email)->accessToken;

            return response()->json(['access_token' => $token]);
        }

        $client = $this->userRepository->getGrantClient();
        if (!$client) {
            abort(404, 'No client found');
        }

        $response = Request::create('oauth/token', 'post', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $request->email,
            'password' => $request->password,
        ]);

        $data = app()->handle($response);

        if ($data->status() !== 200) {
            abort(401, 'Email/Password do not match');
        }

        return $data->content();
    }

    /**
     * Login user.
     *
     * @param AuthRequest $request
     * @return UserResource
     *
     *  @OA\Post(
     *      path="/api/refresh",
     *      tags={"User"},
     *      operationId="refreshUser",
     *      summary="Refresh User",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="refresh_token",
     *                  type="string",
     *                  example="demo",
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Refreshed",
     *      ),
     *  )
     */
    public function refresh(Request $request)
    {
        $client = $this->userRepository->getGrantClient();
        if (!$client) {
            abort(404, 'No client found');
        }

        $response = Request::create('/oauth/token', 'post', [
            'grant_type' => 'refresh_token',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'refresh_token' => $request->refresh_token,
        ]);

        $data = app()->handle($response);

        if ($data->status() !== 200) {
            abort(403, 'Refresh token is invalid');
        }

        return $data->content();
    }

    /**
     * Get auth user info.
     *
     * @return UserResource
     *
     *  @OA\Get(
     *      path="/api/me",
     *      tags={"User"},
     *      operationId="getProfileUser",
     *      summary="Get Auth User",
     *      @OA\Response(
     *          response=200,
     *          description="Getted",
     *      ),
     *  )
     */
    public function getProfile()
    {
        $user = $this->userRepository->detail(auth()->guard('api')->user());

        $user->menus = $this->userRepository->getMenus($user);

        return new UserResource($user);
    }

    /**
     * Update auth user info.
     *
     * @param Request $request
     * @return Response
     *
     *  @OA\Post(
     *      path="/api/me",
     *      tags={"User"},
     *      operationId="postProfileUser",
     *      summary="Update Auth User",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/userWithPassword"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Updated",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/user",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function postProfile(ProfileRequest $request)
    {
        $user = auth()->user();

        $data = $request->only('name');

        if ($request->has('old_password') && $request->has('new_password')) {
            if (!auth()->guard('web')->attempt(['email' => $user->email, 'password' => $request->old_password])) {
                abort(403, 'Password doesn\'t match');
            }
            $data['password'] = bcrypt($request->new_password);
        }

        $this->userRepository->update($user, $data);

        $user->menus = $this->userRepository->getMenus($user);

        return new UserResource($user);
    }

    /**
     * Logout user.
     *
     * @return Response
     *
     *  @OA\Post(
     *      path="/api/logout",
     *      tags={"User"},
     *      operationId="logoutUser",
     *      summary="Logout User",
     *      @OA\Response(
     *          response=204,
     *          description="Logged out",
     *      ),
     *  )
     */
    public function logout()
    {
        $user = auth()->guard('api')->user();

        $user->token()->revoke();

        return response()->json(null, 204);
    }

    /**
     * Forgot password
     *
     * @param Request $request
     */
    public function forgotPassword(Request $request) {
        $request->validate([
            'email' => 'required'
        ]);

        $user = $this->userRepository->getUserByEmail($request->email);
        if (!isset($user)) {
            abort(401, 'Email does not match');
        }

        return $user;
    }
}
