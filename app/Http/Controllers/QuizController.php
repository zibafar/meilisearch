<?php

namespace App\Http\Controllers;

use App\Actions\Meili\Quiz\GetQuizAction;
use App\Http\Requests\Meili\QuizRequestBase;
use App\Http\Resources\Meili\QuizResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Display a listing of the resources.
 *
 * @OA\Get(
 *     path="/quiz",
 *     tags={"Search"},
 *     summary="List of quizs",
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
 *             @OA\Items(ref="#/components/schemas/MeiliQuiz"),
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
class QuizController extends Controller
{
    public function __invoke(QuizRequestBase $request, GetQuizAction $action): JsonResponse
    {
        //permission
        $ability = 'search-in-quiz';
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
                'data'=>QuizResource::collection(collect($itemsNULL['data'])),
                'paginate'=>$itemsNULL['paginate'],
                'meta'=>(new QuizResource([]))->with($request),
            ]
        ];

        return $this->sendResponse($success, 'success.', $ability);
    }


}
