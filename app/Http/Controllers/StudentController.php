<?php

namespace App\Http\Controllers;

use App\Repositories\StudentRepository;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

/**
 *  @OA\Tag(
 *      name="Student",
 *      description="Student Resource",
 * )
 *
 *  @OA\Schema(
 *      schema="students",
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
class StudentController extends Controller
{
    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/students",
     *      tags={"Student"},
     *      operationId="indexStudent",
     *      summary="List Student",
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
     *                  @OA\Items(ref="#/components/schemas/students")
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function index(Request $request)
    {
        $students = $this->studentRepository->list($request->all());

        return StudentResource::collection($students);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StudentRequest $request
     * @return  \Illuminate\Http\Response
     *
     * @param  Request $request
     * @return  Response
     *
     *  @OA\Post(
     *      path="/api/students",
     *      tags={"Student"},
     *      operationId="storeStudent",
     *      summary="Create Student",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/students"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Created",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/students",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function store(StudentRequest $request)
    {
        $data = $request->all();

        $student = $this->studentRepository->create($data);

        return new StudentResource($student);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Get(
     *      path="/api/students/{id}",
     *      tags={"Student"},
     *      operationId="showStudent",
     *      summary="Get Student",
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
     *                  ref="#/components/schemas/students",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function show(Student $student)
    {
        return new StudentResource($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StudentRequest $request
     * @param  \App\Models\Student  $student
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Put(
     *      path="/api/students/{id}",
     *      tags={"Student"},
     *      operationId="updateStudent",
     *      summary="Update Student",
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
     *          @OA\JsonContent(ref="#/components/schemas/students"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Updated",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/students",
     *              ),
     *          ),
     *      ),
     *  )
     */
    public function update(StudentRequest $request, Student $student)
    {
        $data = $request->all();

        $this->studentRepository->update($student, $data);

        return new StudentResource($student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return  \Illuminate\Http\Response
     *
     *  @OA\Delete(
     *      path="/api/students/{id}",
     *      tags={"Student"},
     *      operationId="deleteStudent",
     *      summary="Delete Student",
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
    public function destroy(Student $student)
    {
        if ($student->id == auth()->guard('api')->id()) {
            abort(403, 'Access denied');
        }

        $this->studentRepository->delete($student);

        return response()->json(null, 204);
    }
}
