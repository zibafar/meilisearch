<?php

namespace App\Http\Controllers;

use App\Actions\Meili\Forum\GetForumAction;
use App\Actions\Meili\MeiliActionManager;
use App\Http\Requests\BaseMeiliRequest;
use App\Http\Resources\Meili\AssignmentResource;
use App\Http\Resources\Meili\CourseResource;
use App\Http\Resources\Meili\ForumResource;
use App\Http\Resources\Meili\OtherResource;
use App\Http\Resources\Meili\QuizResource;
use App\Http\Resources\Meili\VideoResource;
use App\Models\Moodle\Meili\MeiliAssign;
use App\Models\Moodle\Meili\MeiliCourse;
use App\Models\Moodle\Meili\MeiliForum;
use App\Models\Moodle\Meili\MeiliOther;
use App\Models\Moodle\Meili\MeiliQuiz;
use App\Models\Moodle\Meili\MeiliVideo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Display a listing of the resources.
 *
 * @OA\Get(
 *     path="/search",
 *     tags={"Search"},
 *     summary="List of all search ",
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
class SearchController extends Controller
{
    public function __invoke(BaseMeiliRequest $request,
                             MeiliActionManager $manager
    ): JsonResponse
    {
        //permission
        $ability = 'search-in-all';

        $search = $request->input('search');
        $filters = $request->input('filters');
        $sort = $request->input('sort');
        $limit = $request->input('limit');
        $page = $request->input('page');

        $indexes = $this->getIndexes();
        $data=[];
        foreach ($indexes as $index => $resource) {
            $filters1 = $filters;
            if ($index == MeiliCourse::INDEX){
                $filters1=[];
            }
            $data[$index] = $manager->driver($index)->run($search, $filters1, $sort, $limit, $page);
        }

        if (eachOneIsFalse($data)) {
            return $this->sendError(__('app.error.500'), [
                'error' => 'Server error',
                'class' => __CLASS__,
                'line' => __LINE__,
            ], 500);
        }

        $result=[];
        foreach ($indexes as $index => $resource) {
            $result[Str::plural($index)]=  [
                'data' => $resource::collection(collect($data[$index]['data'] ?? [])),
                'paginate' =>$data[$index]['paginate'] ?? [],
            ];
        }

        $success = $result + [
            'meta' => (new QuizResource([]))->with($request), //برای ترجمه ها
        ];

        return $this->sendResponse($success, 'success.', $ability);
    }

    /**
     * @return string[]
     */
    private function getIndexes(): array
    {
        return [
            MeiliCourse::INDEX => CourseResource::class,
            MeiliForum::INDEX => ForumResource::class,
            MeiliQuiz::INDEX => QuizResource::class,
            MeiliAssign::INDEX => AssignmentResource::class,
            MeiliVideo::INDEX => VideoResource::class,
            MeiliOther::INDEX => OtherResource::class
        ];
    }


}
