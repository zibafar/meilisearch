<?php

namespace App\Traits\Resource;

use App\Models\Moodle\Meili\MeiliCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait Details
{
    private function detail(array $ids, string $txt, string $type,string $link=null)
    {
        $matched= Str::hasMarker($txt);
        return $this->when($matched || Str::contains($type, [MeiliCourse::INDEX, 'category' ]),[
//       return   [ 'match' => $matched,
            'txt' => $txt,
            'type' => $type,
            'type_fa' => __('component.'.$type),
            // force view use moodle link (completion)
            'link' => /*$link ??*/  $this->getLink($type, $ids)
        ]
        );
    }

    private function getLink(string $type, array $ids): string
    {
        return match ($type) {
            //video component
            //todo link video sample
            'bookmark' => "mod/video/view.php?id={$ids[1]}#b{$ids[2]}",
            'video' => "/mod/video/view.php?id={$ids[1]}",
            //------------
            'category' => "/course/index.php?categoryid={$ids[1]}",
            'course' => "/course/view.php?id={$ids[0]}",
            //forum component
            'post' => "/mod/forum/discuss.php?d={$ids[2]}#p{$ids[3]}",
            'discuss' => "/mod/forum/discuss.php?d={$ids[2]}",
            'forum' => "/mod/forum/view.php?id={$ids[1]}",
            'url' => "/url/view.php?id={$ids[1]}",
            'page' => "/mod/page/view.php?id={$ids[3]}",
            // 2=>cm_id
            'assign' =>"/mod/assign/view.php?id={$ids[2]}",
            //------
            //!quiz
            //0=>coures , 1=>quiz 2=>slot 3=>question 4=>answer 5=>cm_id
            'quiz' => "/mod/quiz/view.php?id={$ids[5]}",
            //todo ?
            'question','answer'=>"/question/edit.php?cmid={$ids[1]}",

        };
    }
    public function with(Request $request): array
    {
        //0=>course , 1=>forum , 2=>discuss  3=>post 4=>url 5=>page
        $components= [
            'category',
            'course',
            'forum',
            'discuss',
            'post',
            'url',
            'page',
            'assign',
            'quiz',
            'answer',
            'question'
        ];
        $result=[];
        foreach ($components as $component){
            $str='مولفه ی ';
            $result[$component]=$str.__('component.'.$component);
        }
        return  $result;
    }

}

