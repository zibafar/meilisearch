<?php

namespace App\Actions\Post;


use App\Models\Moodle\Forum\Discussion;
use App\Models\Moodle\Forum\Forum;
use App\Models\Moodle\Forum\Post;
use App\Models\Moodle\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Meilisearch\Endpoints\Indexes;


class GetPostAction
{
    /**
     * @param string $search
     * @param array $filters
     * @param string $sort
     * @param int $limit
     * @return bool | LengthAwarePaginator
     */
    public function run(string $search, array $filters, string $sort, int $limit): bool|LengthAwarePaginator
    {

        if (isset($filters['course_ids'])) {
            $filters['discussion_ids']=Discussion::query()
                ->whereIn('course',$filters['course_ids'])
                ->pluck('id')->toArray();
        }

//        $filters['ids']=Post::query()->modelKeys();
        $sort = extractSort($sort);
        return Post::search(
            query: $search,
            callback: function (Indexes $meiliSearch, string $query, array $options) use ($filters, $sort) {
                $options['sort'] = [$sort['column'] . ':' . $sort['direction']];

                if (isset($filters['course_ids'])) {
                    $operation = (empty($options['filter'])) ? '' : ' AND ';
                    $options['filter'] = $operation . " discussion IN [ {$filters['discussion_ids']} ] ";
                }

                return $meiliSearch->search($query, $options);
            }
        ) ->query(function ($query) use ($sort){
            return $query->published();
        })
            ->orderBy($sort['column'], $sort['direction'])
            ->paginate($limit);
    }
}
