<?php

namespace App\Http\Controllers;

use App\Repositories\LecturerRepository;
use App\Http\Resources\LecturerResource;
use App\Http\Requests\LecturerRequest;
use App\Models\Lecturer;
use Illuminate\Http\Request;

/**
 *  @OA\Tag(
 *      name="Lecturer",
 *      description="Lecturer Resource",
 * )
 *
 *  @OA\Schema(
 *      schema="lecturers",
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="address",
 *          type="string",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="phone",
 *          type="string",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="specialized",
 *          type="number",
 *          example=1,
 *      ),
 *  )
 */
class LecturerController extends Controller
{
    public function __construct(LecturerRepository $lecturerRepository)
    {
        $this->lecturerRepository = $lecturerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/lecturers",
     *      tags={"Lecturer"},
     *      operationId="indexLecturer",
     *      summary="List Lecturer",
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
     *                  @OA\Items(ref="#/components/schemas/lecturers")
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function index(Request $request)
    {
        $lecturers = $this->lecturerRepository->list($request->all());

        return LecturerResource::collection($lecturers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LecturerRequest $request
     * @return  \Illuminate\Http\Response
     *
     * @param  Request $request
     * @return  Response
     *
     *  @OA\Post(
     *      path="/api/lecturers",
     *      tags={"Lecturer"},
     *      operationId="storeLecturer",
     *      summary="Create Lecturer",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/lecturers"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Created",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/lecturers",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function store(LecturerRequest $request)
    {
        $data = $request->all();

        $lecturer = $this->lecturerRepository->create($data);

        return new LecturerResource($lecturer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lecturer  $lecturer
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/lecturers/{id}",
     *      tags={"Lecturer"},
     *      operationId="showLecturer",
     *      summary="Get Lecturer",
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
     *                  ref="#/components/schemas/lecturers",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function show(Lecturer $lecturer)
    {
        return new LecturerResource($lecturer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LecturerRequest $request
     * @param  \App\Models\Lecturer  $lecturer
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Put(
     *      path="/api/lecturers/{id}",
     *      tags={"Lecturer"},
     *      operationId="updateLecturer",
     *      summary="Update Lecturer",
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
     *          @OA\JsonContent(ref="#/components/schemas/lecturers"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Updated",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/lecturers",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function update(LecturerRequest $request, Lecturer $lecturer)
    {
        $data = $request->all();

        $this->lecturerRepository->update($lecturer, $data);

        return new LecturerResource($lecturer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lecturer  $lecturer
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Delete(
     *      path="/api/lecturers/{id}",
     *      tags={"Lecturer"},
     *      operationId="deleteLecturer",
     *      summary="Delete Lecturer",
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
    public function destroy(Lecturer $lecturer)
    {
        if ($lecturer->id == auth()->guard('api')->id()) {
            abort(403, 'Access denied');
        }

        $this->lecturerRepository->delete($lecturer);

        return response()->json(null, 204);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/list-lecturers",
     *      tags={"Lecturer"},
     *      operationId="getAllLecturer",
     *      summary="List All Lecturer",
     *      @OA\Parameter(ref="#/components/parameters/page"),
     *      @OA\Parameter(ref="#/components/parameters/limit"),
     *      @OA\Parameter(ref="#/components/parameters/sortField"),
     *      @OA\Parameter(ref="#/components/parameters/sortOrder"),
     *      @OA\Response(
     *          response=200,
     *          description="Get all data",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/lecturers")
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function getAllLecturers()
    {
        return $this->lecturerRepository->getAll();
    }
}
