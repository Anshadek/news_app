<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;

class ArticleController extends BaseController
{

    //get all article list and check if article is bookmarked
    public function articleList(Request $request)
    {
        try {
            $userId = auth()->user()->id;

            $articles = Article::with(['users' => function ($query) use ($userId) {
                $query->where('users.id', $userId);
            }])
                ->paginate(10)
                ->map(function ($article) {
                    $article->isBookmarked = $article->users->isNotEmpty();
                    return $article;
                });

            return $this->sendResponse($articles, 'success');
        } catch (\Exception $e) {
            return $this->sendError('Something went wrong', [], 500);
        }
    }




    //add article to bookmark for authenticated user
    public function addToBookMark(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'article_id' => [
                    'required',
                    Rule::unique('bookmarks')->where(function ($query) {
                        return $query->where('user_id', auth()->user()->id);
                    }),
                ],
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $bookmark = new Bookmark();
            $bookmark->user_id = auth()->user()->id;
            $bookmark->article_id = $request->article_id;
            if ($bookmark->save()) {
                return $this->sendResponse([], 'Successfully bookmarked');
            } else {
                return $this->sendError('Something went wrong', [], 500);
            }
        } catch (\Exception $e) {
            return $this->sendError('Something went wrong', [], 500);
        }
    }



    //get bookmarked article for authenticated user
    public function getBookmarkedArticles()
    {
        try {
            $res_data = User::find(auth()->user()->id);
            $res_data = $res_data->articles()->paginate(10);
            return $this->sendResponse($res_data, 'success');
        } catch (\Exception $e) {
            return $this->sendError('Something went wrong', [], 500);
        }
    }
}
