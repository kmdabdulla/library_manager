@extends('layouts.navbar')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>{{Auth::user()->name}} Activity</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
 <!--display logs-->
@if (@isset($logs) && $logs->isNotEmpty())
<div class="container-fluid">
   <div class="row">
   <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Activity Log</h3>
          </div>
          <div class="card-body table-responsive p-0" style="height: 500px;">
            <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>ISBN</th>
                        <th>Status</th>
                        <th>Activity Time</th>
                    </tr>
                </thead>
              <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{$log->title}}</td>
                        <td>{{$log->isbn}}</td>
                        <td>{{$log->action}}</td>
                        <td>{{$log->created_at}}</td>
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
</section>
@endsection
