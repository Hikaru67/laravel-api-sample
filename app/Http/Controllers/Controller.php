<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 *  @OA\OpenApi(
 *      security={
 *          {"bearerAuth": {}}
 *      }
 *  )
 *
 *  @OA\Info(
 *      description="API Document",
 *      version="1.0.0",
 *      title="API",
 *  )
 *
 *  @OA\SecurityScheme(
 *      type="http",
 *      securityScheme="bearerAuth",
 *      scheme="bearer"
 *  )
 *
 *  @OA\Parameter(
 *      name="page",
 *      in="query",
 *      @OA\Schema(
 *          type="integer",
 *          format="int64",
 *      )
 *  )
 *
 *  @OA\Parameter(
 *      name="limit",
 *      in="query",
 *      @OA\Schema(
 *          type="integer",
 *          format="int64",
 *      )
 *  )
 *
 *  @OA\Parameter(
 *      name="sort",
 *      in="query",
 *  )
 *
 *  @OA\Parameter(
 *      name="sortType",
 *      in="query",
 *      @OA\Schema(
 *          type="integer",
 *          format="int64",
 *      )
 *  )
 *
 *  @OA\Parameter(
 *      name="condition",
 *      in="query",
 *  )
 *
 *  @OA\Schema(
 *      schema="meta",
 *      @OA\Property(
 *          property="current_page",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="from",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="last_page",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="path",
 *          type="string",
 *          example="http://abc.com",
 *      ),
 *      @OA\Property(
 *          property="per_page",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="to",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="total",
 *          type="number",
 *          example=1,
 *      ),
 *  )
 *
 *  @OA\Schema(
 *      schema="links",
 *      @OA\Property(
 *          property="first",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="last",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="prev",
 *          type="number",
 *          example=1,
 *      ),
 *      @OA\Property(
 *          property="next",
 *          type="number",
 *          example=1,
 *      ),
 *  )
 *
 *  @OA\Schema(
 *      schema="list",
 *      @OA\Property(
 *          property="id",
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
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
