@extends('admin.layouts.app')

@section('content')
<style>

.card {
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  margin: 20px;
  padding: 20px;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* Main Container */
.main-container {
  display: flex;
  justify-content: center;
  align-items: center;
}

/* Drop Zone */
.drop-zone {
  width: 300px;
  height: 200px;
  border: 2px dashed #FF5A5F;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
}

/* Drop Zone Text */
.drop-zone-text {
  color: #FF5A5F;
  font-size: 16px;
}

/* Highlighted Drop Zone */
.drop-zone.dragged-over {
  background-color: rgba(255, 90, 95, 0.2);
}

/* Drop Zone Icons */
.drop-zone-icons {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 10px;
}

.upload-icon {
  height: 48px;  /* Increased icon size */
  width: 48px;   /* Increased icon size */
  margin-bottom: 10px;
}

/* Drop Zone Text */
.drop-zone-text {
  text-align: center;
  font-size: 16px;
  margin-top: 10px; /* Added to separate text from icons */
}
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <a class="btn-sm btn-success" href="{{ route('article.index') }}"> <i class="fa fa-arrow-left" aria-hidden="true"></i></a>
        <!-- <h1>Show Role</h1> -->
        <div class="pull-right">
        </div>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active">Import New {{$page_name}}</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>



<div class="col-md-12">
  <!-- general form elements -->
  <form action="{{ route('article.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
  <div class="card card-primary">
    @if (count($errors) > 0)
    <div class="alert alert-danger">
      <strong>Whoops!</strong> There were some problems with your input.<br><br>
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
      <div class="row">
      </div>
<!-- Product Card Container -->
  <!-- Main Container -->
  <div class="main-container">
    <div id="dropZone" class="drop-zone">
      <div class="drop-zone-icons">
        <svg class="upload-icon" viewBox="0 0 24 24" fill="#FF5A5F"><path d="M12 6l-4 4h3v4h2v-4h3m-10 6v2h12v-2h-12z"></path></svg>
      </div>
      <span class="drop-zone-text">Drag and drop excel file or json file here or click to upload</span>
    </div>
  </div>
    </div>
      <button type="submit" class="btn btn-primary">Submit</button>
  </div>
  </form>
</div>
@endsection
@section('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
  const dropZone = document.getElementById("dropZone");

  // Create hidden file input
  const fileInput = document.createElement("input");
  fileInput.type = "file";
  fileInput.multiple = true;
  fileInput.name = "article_file";
  fileInput.style.display = "none";
  fileInput.addEventListener("change", () => handleFiles(fileInput.files));
  
  // Append file input to main container
  dropZone.appendChild(fileInput);

  dropZone.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZone.classList.add("dragged-over");
  });

  dropZone.addEventListener("dragleave", () => {
    dropZone.classList.remove("dragged-over");
  });

  dropZone.addEventListener("drop", (e) => {
    e.preventDefault();
    dropZone.classList.remove("dragged-over");
    const files = e.dataTransfer.files;
    handleFiles(files);
  });

  // Trigger file input on click
  dropZone.addEventListener("click", () => {
    fileInput.click();
  });
});

function handleFiles(files) {
  console.log(`Received ${files.length} files`);
}

</script>
@endsection
