<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 *  @OA\OpenApi(
 *      security={
 *          {"bearerAuth": {}},
 *          {"passport": {}}
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
 *      name="sortOrder",
 *      in="query",
 *  )
 *
 *  @OA\Parameter(
 *      name="sortField",
 *      in="query",
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
