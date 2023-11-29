@extends('admin.layouts.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
@section('content')
    <section class="content-header">

        <div class="container-fluid">

            @if ($message = Session::get('success'))
                <div class="alert alert-success text-center ml-5 mr-5">
                    <!-- you missed this line of code -->
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success!</strong> {{ $message }}
                </div>
            @endif
            @if ($message = Session::get('fail'))
                <div class="alert alert-danger text-center ml-5 mr-5">
                    <!-- you missed this line of code -->
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Fail!</strong> {{ $message }}
                </div>
            @endif
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>{{ $page_name }} Management</h2>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ $page_name }} Management</li>
                    </ol>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- /.card -->

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">{{ $page_name }} List</h3>

                <div class="ml-auto">
                    <a class="btn btn-primary btn-md" href="{{ asset('demoImportFile/articles_csv.csv') }}">Demo Excel</a>
                       
                    <a class="btn btn-primary btn-md" href="{{ asset('demoImportFile/article_json.json') }}}"  download="article_json.json">Demo Json</a>
                    
                    <a class="btn btn-success btn-lg" href="{{ route('article.create') }}"><i
                            class="nav-icon fas fa-plus"></i></a>
                </div>


            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped ">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Date</th>
                            <th>Source</th>
                            @if ( Auth::user()->is_admin == 1)
                            <th>Action</th>    
                            @endif
                           
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($articles as $key => $article)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->content }}</td>
                                <td>{{ date('F j, Y', strtotime($article->date)) }}</td>
                                <td>{{ $article->source }}</td>
                                @if ( Auth::user()->is_admin == 1)
                                <td>
                                    <form id="deleteForm{{ $article->id }}" action="{{ route('article.destroy', $article->id) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" onclick="confirmDelete({{ $article->id }})" class="btn btn-danger">
                                            <i class="fa fa-fw fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                @endif

                            </tr>
                            @empty
                <tr>
                    <td colspan="6" class="text-center">No articles found.</td>
                </tr>
                        @endforelse


                    </tbody>
                   
                    <nav aria-label="Page navigation">
                        <ul class="pagination" id="pagination"></ul>
                    </nav>
                </table>
                <div class="mt-3">
                    {!! $articles->withQueryString()->links('pagination::bootstrap-5') !!}
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection('content')
@section('scripts')
<script>
    function confirmDelete(articleId) {
        var confirmation = confirm("Are you sure you want to delete this article?");
        
        if (confirmation) {
            document.getElementById('deleteForm' + articleId).submit();
        }
    }
</script>
@endsection

<!-- /.content-wrapper -->
