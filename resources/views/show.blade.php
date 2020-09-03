@extends('layouts.app')

@section('content')

    <input type="hidden" name="id" value="{!! $bookmark->id !!}" />
    {{ csrf_field() }}

    @if($bookmark->password)
        <a href="/" class="float-right btn btn-outline-danger mb-3" data-toggle="modal" data-target="#exampleModal">Delete</a>
    @endif

    <a href="/" class="btn btn-outline-primary mb-3">Back</a>
    <div class="form-group">
        <label for="exampleInputEmail1">URL</label>
        <input readonly type="text" class="form-control" name="url" aria-describedby="url" placeholder="url" value="{!! $bookmark->url !!}">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Title</label>
        <input readonly type="text" class="form-control" name="url" aria-describedby="title" placeholder="title" value="{!! $bookmark->title !!}">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Description</label>
        <input readonly type="text" class="form-control" name="url" aria-describedby="description" placeholder="description" value="{!! $bookmark->desc !!}">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Keywords</label>
        <input readonly type="text" class="form-control" name="url" aria-describedby="keywords" placeholder="keywords" value="{!! $bookmark->keywords !!}">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Favicon</label>
        <img src="{!! $bookmark->favicon !!}" />
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" aria-describedby="url" placeholder="password">
                        <small class="form-text text-muted">Enter password you set for this bookmark</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="delete-btn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="/js/custom.js"></script>

@endsection
