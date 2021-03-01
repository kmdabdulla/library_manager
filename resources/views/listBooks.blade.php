@extends('layouts.navbar')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
            @if(@isset($data['listType']))
          <h1>{{($data['listType']=="available")?'Available':'Checked Out'}} Books List</h1>
          @endif
        </div>
      </div>
    </div>
  </section>

  <section class="content">
<!-- error/success message div -->
@if(session()->has('response') || (isset($errors) && count($errors) > 0))
    <div class="alert alert-{{session('response.status') ?? 'danger'}} alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ session('response.message') ?? $errors->first() }}
    </div>
@endif
 <!--display books-->
@if (@isset($data['books']) && $data['books']->isNotEmpty())
<div class="container-fluid">
   <div class="row">
   <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            @if(@isset($data['listType']))
            <h3 class="card-title">{{($data['listType']=="available")?'Available books in Library':'Checked Out books from Library'}}</h3>
            @endif
          </div>
          <div class="card-body table-responsive p-0" style="height: 500px;">
            <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>ISBN</th>
                        <th>Published Date</th>
                        <th width="100px">Action</th>
                    </tr>
                </thead>
              <tbody>
                @foreach($data['books'] as $book)
                    <tr>
                        <td>{{$book->title}}</td>
                        <td>{{$book->isbn}}</td>
                        <td>{{$book->published_at}}</td>
                        <td>
                    <button id="changeBookStatusButton" class="btn btn-danger .btn-sm" data-info="{{$book->id}}, {{$book->title}}" data-toggle="modal" data-target="#changeBookStatusModal">
                        <span class="fa fa-book"></span> @if(@isset($data['listType']))
                        {{($data['listType']=="available")?'Check Out':'Check In'}}
                        @endif
                    </button>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
@endif

<!-- change book status model -->
    <div class="modal fade" id="changeBookStatusModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                @if(@isset($data['listType']))
              <h4 class="modal-title">{{($data['listType']=="available")?'CheckOut':'CheckIn'}} Confirmation</h4>
              @endif
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="performUserAction" method="post">@csrf
                    <input id="bookId" type="hidden" name="bookId" value=""/>
                    @if(@isset($data['listType']))
                    <input id="action" type="hidden" name="action" value={{($data['listType']=="available")?'CHECKOUT':'CHECKIN'}} />
              <p>Are you sure about {{($data['listType']=="available")?'checking out':'checking in'}} <span id="bookName"></span>?</p>
            </div>
            @endif
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
</section>
@endsection
