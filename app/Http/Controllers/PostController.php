<?php

namespace App\Http\Controllers;

use App\Actions\Post\GetPostAction;
use App\Http\Requests\Forum\PostRequest;
use App\Http\Resources\Post\PostPaginateResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Display a listing of the resources.
 *
 * @OA\Get(
 *     path="/post",
 *     tags={"Search"},
 *     summary="List of pots",
 *    @OA\Parameter(name="search", description="search", example="دکتر عبادی", required=true, @OA\Schema(type="string"), in="query"),
 *    @OA\Parameter(name="page", description="page of tickets", example="1", required=false, @OA\Schema(type="integer"), in="query"),
 *    @OA\Parameter(name="limit", description="limit per page", example="10", required=false, @OA\Schema(type="integer"), in="query"),
 *    @OA\Parameter(name="sort", description="sort items", example="modified_desc", required=false, @OA\Schema(type="string"), in="query"),
 *    @OA\Parameter(name="filters", description="filters is an object include course_ids ",
 *     required=false,
 *     example="{""discussion_ids"":""1,2,3"",""course_ids"":""1,2,3""}",
 *     style="deepObject",
 *     @OA\Schema(type="object"),
 *      in="query"),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Quiz"),
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
class PostController extends Controller
{
    public function __invoke(PostRequest $request, GetPostAction $action): JsonResponse
    {
        //permission
        $ability = 'search-in-post';
        //init
        $filters = $request->input('filters')??[];
        $limit = $request->input('limit') ?? 50;
        $sort = $request->input('sort') ?? 'modified_desc';
        $search = $request->input('search') ?? '';

        //run action and (log binding in service provider)
        $itemsNULL = $action->run($search, $filters, $sort, $limit);

        if ($itemsNULL === false) {
            return $this->sendError(__('app.error.500'), [
                'error' => 'Server error',
                'class' => __CLASS__,
                'line' => __LINE__,
            ], 500);
        }

        $success = [
            'result' => PostPaginateResource::make($itemsNULL),
        ];

        return $this->sendResponse($success, 'success.', $ability);
    }


}
