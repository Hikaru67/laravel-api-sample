<?php

namespace App\Http\Controllers;

use App\Repositories\ThesisRepository;
use App\Http\Resources\ThesisResource;
use App\Http\Requests\ThesisRequest;
use App\Models\Thesis;
use Illuminate\Http\Request;

/**
 *  @OA\Tag(
 *      name="Thesis",
 *      description="Thesis Resource",
 * )
 *
 *  @OA\Schema(
 *      schema="theses",
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="attaches",
 *          type="string",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="student_id",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="lecturer_id",
 *          type="number",
 *          example=1,
 *      ),
 *  )
 */
class ThesisController extends Controller
{
    public function __construct(ThesisRepository $thesisRepository)
    {
        $this->thesisRepository = $thesisRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/theses",
     *      tags={"Thesis"},
     *      operationId="indexThesis",
     *      summary="List Theses",
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
     *                  @OA\Items(ref="#/components/schemas/theses")
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function index(Request $request)
    {
        $theses = $this->thesisRepository->list($request->all(), ['student', 'lecturer']);

        return ThesisResource::collection($theses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ThesisRequest $request
     * @return  \Illuminate\Http\Response
     *
     * @param  Request $request
     * @return  Response
     *
     *  @OA\Post(
     *      path="/api/theses",
     *      tags={"Thesis"},
     *      operationId="storeThesis",
     *      summary="Create Thesis",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/theses"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Created",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/theses",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function store(ThesisRequest $request)
    {
        $data = $request->only('name', 'email');
        $data['password'] = bcrypt($request->password);

        $thesis = $this->thesisRepository->create($data);

        if ($request->has('roles')) {
            $this->thesisRepository->syncRoles($thesis, $request->roles);
        }

        return new ThesisResource($thesis);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Thesis  $thesis
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/theses/{id}",
     *      tags={"Thesis"},
     *      operationId="showThesis",
     *      summary="Get Thesis",
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
     *                  ref="#/components/schemas/theses",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function show(Thesis $thesis)
    {
        return new ThesisResource($thesis->load(['student', 'lecturer']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ThesisRequest $request
     * @param  \App\Models\Thesis  $thesis
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Put(
     *      path="/api/theses/{id}",
     *      tags={"Thesis"},
     *      operationId="updateThesis",
     *      summary="Update Thesis",
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
     *          @OA\JsonContent(ref="#/components/schemas/theses"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Updated",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/theses",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function update(ThesisRequest $request, Thesis $thesis)
    {
        $data = $request->only('name', 'email');

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $this->thesisRepository->update($thesis, $data);

        if ($request->has('roles')) {
            $this->thesisRepository->syncRoles($thesis, $request->roles);
        }

        return new ThesisResource($thesis);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Thesis  $thesis
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Delete(
     *      path="/api/theses/{id}",
     *      tags={"Thesis"},
     *      operationId="deleteThesis",
     *      summary="Delete Thesis",
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
    public function destroy(Thesis $thesis)
    {
        if ($thesis->id == auth()->guard('api')->id()) {
            abort(403, 'Access denied');
        }

        $this->thesisRepository->delete($thesis);

        return response()->json(null, 204);
    }
}
