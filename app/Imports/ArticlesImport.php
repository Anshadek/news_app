<?php

namespace App\Imports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArticlesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
    
            return new Article([
                'title'     => $row['title'],
                'content'    => $row['content'], 
                'source'    => $row['source'], 
                'date' => $row['date'], 
            ]);
      
    }
}
