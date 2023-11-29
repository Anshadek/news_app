<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\fileImportTrait;

class ArticleController extends Controller
{
    use fileImportTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::paginate(10);
        $page_name = 'Article';
        return view('admin.article.index', [
        'page_name' => $page_name,
         'articles' => $articles,]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page_name = 'Article';
        return view('admin.article.create', ['page_name' => $page_name]);
    }

    /**
     * Store a newly created resource in storage.
     */
    //this function is used to import file from excel or json and redirect to index
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'article_file' => 'required|mimes:xlsx,xls,csv,json',
        ]);

        $uploadedFile = $request->file('article_file');
        $extension = $uploadedFile->getClientOriginalExtension();

        if ($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv') { 
            $res = $this->importExcelFile($uploadedFile);
        } else if ($extension == 'json') {
            $path = $uploadedFile->store('articles');
            $res = $this->importJsonFile($path);
        }
        return redirect('article')->with($res['status'], $res['message']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    //this function is used to delete article from database and redirect to index
    public function destroy(string $id)
    {
        try {
            $article = Article::find($id);

            if ($article) {
                $article->delete();

                return redirect()->route('article.index')
                    ->with('success', 'Article deleted successfully');
            } else {
                return redirect()->route('article.index')
                    ->with('fail', 'Article not found');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('fail', 'Something went wrong!');
        }
    }
}
