@extends('layouts.app')

@section('content')

    <a href="/" class="btn btn-outline-primary mb-3">Back</a>
    <form action="/bookmarks/create" method="POST">
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1">URL</label>
            <input type="text" class="form-control" name="url" aria-describedby="url" placeholder="url">
            <small id="emailHelp" class="form-text text-muted">Page url you want to add</small>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Password</label>
            <input type="password" class="form-control" name="password" aria-describedby="password" placeholder="password">
            <small id="emailHelp" class="form-text text-muted">Let it free if u wont set any</small>
        </div>
        @if($errors->any())
            <div class="alert alert-danger" role="alert">{!! $errors->first() !!}</div>
        @endif
        <button type="submit" class="btn btn-primary">Add</button>
    </form>

@endsection
