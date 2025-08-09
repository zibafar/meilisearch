<?php

namespace Tests\Feature\Http\Controller;

use App\Models\Moodle\Course;
use App\Models\Moodle\Forum\Discussion;
use App\Models\Moodle\Forum\Post;
use App\Models\Moodle\Quiz;
use App\Models\Moodle\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\Http\__TestCase;

class PostControllerTest extends __TestCase
{
    use WithoutMiddleware;
    private User $user;
    private User $teacher;
    private Course $course;
    private Quiz $quiz;
    private array $structure;


    protected string $indexRouteName;

    protected function setUp(): void
    {
        parent::setUp();
        $this->discussion=Discussion::factory()->createOne();
        $this->post = Post::factory(['discussion'=>$this->discussion->id])->createOne();
//        $this->quiz = Quiz::factory(['course'=>$this->course->id])->viaWebcam()->createOne();

        $this->indexRouteName ='post';

        $this->structure = [
            'ability',
            'success',
            'message',
            'result' => [
                'posts' => [
                    "*" => [
                        "id",
                        "name",
                        "time_open",
                        "time_close"
                    ]
                ],
                "data" => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    "total_pages",
                    "next_page",
                    "previous_page",
                ]
            ]

        ];
    }

    /*--------------------------The index method's tests begin here---------------------------------------*/


    /*-----------------------Different sort of index method's tests begin here----------------------------------*/

    private function doTestSorting($target,string $sort,string $search): void
    {

        Artisan::call('import:post');

        $this
//            ->actingAs($user, 'api')
            ->get( str_replace('/searcher','',
                route($this->indexRouteName,compact('sort','search'))))
            ->assertSuccessful()
            ->assertJsonStructure($this->structure)
            ->assertJson(fn(AssertableJson $json) =>
            $json->has('result.posts', fn(AssertableJson $json1) =>
            $json1
                ->where('0.id', $target->id)
                ->where('0.subject', $target->subject)
                ->etc()
            )->etc()
            );
    }

    public function test_sorting_according_to_descending_modified_works_correctly()
    {
        Post::factory([ 'discussion' => $this->discussion->id])->createOne();

        $targetService =  Post::factory(['discussion' => $this->discussion->id])->createOne();

        $this->doTestSorting($targetService, 'modified_asc',$targetService->subject);
    }







    /*--------------------------Different sort of index method's tests end here--------------------------*/

    /*--------------------------The index method's tests end here---------------------------------------*/
}
