@extends('layouts.navbar')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Add Book To Library</h1>
        </div>
      </div>
    </div>
  </section>
  <section class="content">

 <!--registration dialog -->
 <div class="container-fluid">

    <div class="row">
    <div class="col-md-5">
                <!--error display div -->
    @if(isset($errors) && count($errors) > 0 )
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        There were {{count($errors->all())}} Error(s)
        <ul class="p-0 m-0" style="list-style: none;">
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <!-- error/success message div -->
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('success')}}
        </div>
    @endif
  <!--<div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Add book to Library</p>-->

      <form action="addBookToLibrary" method="post">
          @csrf
        <div class="input-group mb-3">
          <input type="text" name="title" class="form-control" placeholder="Title">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-book"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" name="isbn" class="form-control" placeholder="ISBN-10">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-barcode"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
        <div class="input-group date" id="publishdate" data-target-input="nearest">
            <input type="text" name="publishedDate" class="form-control datetimepicker-input" placeholder="Select publication date" data-target="#publishdate"/>
            <div class="input-group-append" data-target="#publishdate" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
        </div>
        </div>
        <div class="row">
            <div class="col-8">
            </div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </div>
        </form>

  <!--</div>
</div>-->
</div>
    </div>

</div>
</section>
@endsection
