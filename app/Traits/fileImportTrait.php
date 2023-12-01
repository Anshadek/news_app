<?php

namespace App\Traits;
use Illuminate\Http\Request;
use App\Imports\ArticlesImport ;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

trait fileImportTrait{
public function importJsonFile($path){
    try {
        return DB::transaction(function () use ($path) {
            $absolutePath = storage_path('app/articles/' . basename($path));
            $jsonContent = file_get_contents($absolutePath);
            if ($jsonContent === false) {
                return [
                    'status' => 'fail',
                    'message' => "Unable to read JSON file",
                ];
                
            }       
            
            $articles = json_decode($jsonContent, true);
           
            
            $filteredArticles = [];
            foreach ($articles as $article) {
                if (isset($article['title']) && !empty($article['title']) &&
                    isset($article['content']) && !empty($article['content'])) {
                    $filteredArticles[] = $article;
                }
            }
            

            Article::insert($filteredArticles);
            return [
                'status' => 'success',
                'message' => "JSON file  successfully imported.",
            ];
        });
    } catch (\Exception $e) {
        Log::channel('file_import_log')->error("Failed to import JSON file at path: $path. Error: " . $e->getMessage());
       return [
           'status' => 'fail',
           'message' => "Something went wrong.try again",
       ];
    }
}


public function importExcelFile($path){
    try {
        Excel::import(new ArticlesImport, $path);
        return [
            'status' => 'success',
            'message' => "Excel file  successfully imported",
        ];
    } catch (\Exception $e) {
        Log::error("Failed to import Excel file at path: $path. Error: " . $e->getMessage());
        return [
            'status' => 'fail',
            'message' => "Something went wrong.try again",
        ];
    }
}

}