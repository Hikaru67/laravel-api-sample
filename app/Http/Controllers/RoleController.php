<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 *  @OA\Tag(
 *      name="Role",
 *      description="Role Resource",
 * )
 *
 *  @OA\Schema(
 *      schema="role",
 *      @OA\Property(
 *          property="name",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="guard_name",
 *          type="number",
 *          example=1,
 *      ),
 *  )
 */
class RoleController extends Controller
{
    /**
     * @var  roleRepository
     */
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/role",
     *      tags={"Role"},
     *      operationId="indexRole",
     *      summary="List Role",
     *      @OA\Parameter(ref="#/components/parameters/page"),
     *      @OA\Parameter(ref="#/components/parameters/limit"),
     *      @OA\Parameter(ref="#/components/parameters/sort"),
     *      @OA\Parameter(ref="#/components/parameters/sortType"),
     *      @OA\Parameter(ref="#/components/parameters/condition"),
     *      @OA\Response(
     *          response=200,
     *          description="Listed",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/role")
     *              ),
     *              @OA\Property(
     *                  property="meta",
     *                  ref="#/components/schemas/meta"
     *              ),
     *              @OA\Property(
     *                  property="links",
     *                  ref="#/components/schemas/links"
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function index(Request $request)
    {
        $roles = $this->roleRepository->list($request->all());

        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RoleRequest $request
     * @return  \Illuminate\Http\Response
     *
     * @param  Request $request
     * @return  Response
     *
     *  @OA\Post(
     *      path="/api/role",
     *      tags={"Role"},
     *      operationId="storeRole",
     *      summary="Create Role",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/role"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Created",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/role",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function store(RoleRequest $request)
    {
        $data = $request->only('name');
        $data['guard_name'] = 'web';

        $role = $this->roleRepository->create($data);

        if ($request->permissions) {
            $this->roleRepository->syncPermissions($role, $request->permissions);
        }

        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/role/{id}",
     *      tags={"Role"},
     *      operationId="showRole",
     *      summary="Get Role",
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
     *                  ref="#/components/schemas/role",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function show(Role $role)
    {
        if ($role->name == config('constant.admin_role')) {
            return response()->json(['message' => 'Access denied']);
        }

        $role = $this->roleRepository->detail($role, ['permissions']);

        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RoleRequest $request
     * @param  \App\Models\Role  $role
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Put(
     *      path="/api/role/{id}",
     *      tags={"Role"},
     *      operationId="updateRole",
     *      summary="Update Role",
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
     *          @OA\JsonContent(ref="#/components/schemas/role"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Updated",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/role",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function update(RoleRequest $request, Role $role)
    {
        if ($role->name == config('constant.admin_role')) {
            return response()->json(['message' => 'Access denied']);
        }

        $data = $request->only('name');
        $data['guard_name'] = 'web';

        $this->roleRepository->update($role, $data);

        if ($request->permissions) {
            $this->roleRepository->syncPermissions($role, $request->permissions);
        }

        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Delete(
     *      path="/api/role/{id}",
     *      tags={"Role"},
     *      operationId="deleteRole",
     *      summary="Delete Role",
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
    public function destroy(Role $role)
    {
        if ($role->name == config('constant.admin_role')) {
            return response()->json(['message' => 'Access denied']);
        }

        $this->roleRepository->delete($role);

        return response()->json(null, 204);
    }

    /**
     * Get permission list.
     *
     * @return Response
     *
     *  @OA\Get(
     *      path="/api/permission",
     *      tags={"Role"},
     *      operationId="permission",
     *      summary="List Permission",
     *      @OA\Response(
     *          response=200,
     *          description="Listed",
     *      ),
     *  )
     */
    public function getPermissions()
    {
        $permissions = $this->roleRepository->getPermissions();

        return PermissionResource::collection($permissions);
    }
}
