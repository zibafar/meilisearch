<?php

namespace App\Http\Controllers;

use App\Actions\Meili\Assignment\GetAssignAction;
use App\Http\Requests\Meili\AssignmentRequestBase;
use App\Http\Resources\Meili\AssignmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Display a listing of the resources.
 *
 * @OA\Get(
 *     path="/assign",
 *     tags={"Search"},
 *     summary="List of assigns",
 *    @OA\Parameter(name="search", description="search", example="دکتر عبادی", required=true, @OA\Schema(type="string"), in="query"),
 *    @OA\Parameter(name="page", description="page of request", example="1", required=false, @OA\Schema(type="integer"), in="query"),
 *    @OA\Parameter(name="limit", description="limit per page", example="10", required=false, @OA\Schema(type="integer"), in="query"),
 *    @OA\Parameter(name="sort", description="sort items", example="modified_desc", required=false, @OA\Schema(type="string"), in="query"),
 *    @OA\Parameter(name="filters", description="filters is an object include course_ids ",
 *     required=false,
 *     example="{""course_ids"":""1,2,3""}",
 *     style="deepObject",
 *     @OA\Schema(type="object"),
 *      in="query"),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/MeiliAssign"),
 *         ),
 *     ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 * )
 * )
 *
 * @param Request $request
 *
 * @return JsonResponse [string] message
 */
class AssignmentController extends Controller
{
    public function __invoke(AssignmentRequestBase $request, GetAssignAction $action): JsonResponse
    {
        //permission
        $ability = 'search-in-assign';
        $itemsNULL = $action->run( $request->input('search'),  $request->input('filters'),
            $request->input('sort'),  $request->input('limit'),
            $request->input('page'));

        if ($itemsNULL === false) {
            return $this->sendError(__('app.error.500'), [
                'error' => 'Server error',
                'class' => __CLASS__,
                'line' => __LINE__,
            ], 500);
        }

        $success = [
            'result' => [
                'data'=>AssignmentResource::collection(collect($itemsNULL['data'])),
                'paginate'=>$itemsNULL['paginate'],
                'meta'=>(new AssignmentResource([]))->with($request),
            ]
        ];

        return $this->sendResponse($success, 'success.', $ability);
    }


}
