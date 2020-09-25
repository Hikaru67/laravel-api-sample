<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuRequest;
use App\Http\Requests\MoveRequest;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Repositories\MenuRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 *  @OA\Tag(
 *      name="Menu",
 *      description="Menu Resource",
 * )
 *
 *  @OA\Schema(
 *      schema="menu",
 *      @OA\Property(
 *          property="title",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="link",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="icon",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="parent_id",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="position",
 *          type="number",
 *          example=1,
 *      ),
 *  )
 */
class MenuController extends Controller
{
    /**
     * @var  menuRepository
     */
    protected $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/menu",
     *      tags={"Menu"},
     *      operationId="indexMenu",
     *      summary="List Menu",
     *      @OA\Parameter(ref="#/components/parameters/page"),
     *      @OA\Parameter(ref="#/components/parameters/limit"),
     *      @OA\Parameter(ref="#/components/parameters/sortField"),
     *      @OA\Parameter(ref="#/components/parameters/sortOrder"),
     *      @OA\Parameter(ref="#/components/parameters/condition"),
     *      @OA\Response(
     *          response=200,
     *          description="Listed",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/menu")
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
        $menus = $this->menuRepository->list($request->all(), ['roles']);

        return MenuResource::collection($menus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\MenuRequest $request
     * @return  \Illuminate\Http\Response
     *
     * @param  Request $request
     * @return  Response
     *
     *  @OA\Post(
     *      path="/api/menu",
     *      tags={"Menu"},
     *      operationId="storeMenu",
     *      summary="Create Menu",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/menu"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Created",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/menu",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function store(MenuRequest $request)
    {
        $lastPosition = $this->menuRepository->getMaxPosition() ?? 0;
        $data = $request->all();
        $data['position'] = $lastPosition + 1;
        $menu = $this->menuRepository->create($data);

        if ($request->has('roles')) {
            $this->menuRepository->syncRoles($menu, $request->roles);
        }

        return new MenuResource($menu);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/menu/{id}",
     *      tags={"Menu"},
     *      operationId="showMenu",
     *      summary="Get Menu",
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
     *                  ref="#/components/schemas/menu",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function show(Menu $menu)
    {
        return new MenuResource($menu->load('roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\MenuRequest $request
     * @param  \App\Models\Menu  $menu
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Put(
     *      path="/api/menu/{id}",
     *      tags={"Menu"},
     *      operationId="updateMenu",
     *      summary="Update Menu",
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
     *          @OA\JsonContent(ref="#/components/schemas/menu"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Updated",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/menu",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function update(MenuRequest $request, Menu $menu)
    {
        $this->menuRepository->update($menu, $request->all());

        if ($request->has('roles')) {
            $menu->load('menus');
            $this->menuRepository->syncRoles($menu, $request->roles);
            $this->menuRepository->syncRolesDeep($menu->menus, $request->roles);
        }

        return new MenuResource($menu);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Delete(
     *      path="/api/menu/{id}",
     *      tags={"Menu"},
     *      operationId="deleteMenu",
     *      summary="Delete Menu",
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
    public function destroy(Menu $menu)
    {
        $this->menuRepository->delete($menu);

        return response()->json(null, 204);
    }

    /**
     * Moving a list menu.
     *
     * @param MoveRequest $request
     *
     * @return Response
     *
     *  @OA\Post(
     *      path="/api/menu/move",
     *      tags={"Menu"},
     *      operationId="moveMenu",
     *      summary="Move Menu",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="list",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/list")
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Moved",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/menu")
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
    public function move(MoveRequest $request)
    {
        $list = collect($request->list);

        // Get list id
        $filter = [
            'ids' => $list->map(function ($item) {
                return $item['id'];
            })->toArray(),
        ];

        $menus = $this->menuRepository->list($filter);

        foreach ($menus as $menu) {
            // find the id then update
            $data = $list->firstWhere('id', $menu->id);
            if ($data) {
                $this->menuRepository->update($menu, $data);
            }
        }

        return MenuResource::collection($menus);
    }
}
