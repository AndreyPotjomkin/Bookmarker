@extends('layouts.app')

@section('content')

    <a href="/get-excel" class="float-right btn btn-outline-success mb-3">Download Excel</a>
    <a href="/bookmarks/create" class="btn btn-outline-primary mb-3">Add</a>

    {{ $bookmarks->links() }}

    @if(count($bookmarks) > 0)
        <table id="productSizes" class="table">
            <thead class="thead-light">
            <tr class="d-flex">
                <th class="col-2"><a href="{{ Request::fullUrlWithQuery(['sortBy' => 'created_at', 'orderBy' => $orderBy]) }}">Created at</a></th>
                <th class="col-1">Favicon</th>
                <th class="col-5"><a href="{{ Request::fullUrlWithQuery(['sortBy' => 'url', 'orderBy' => $orderBy]) }}">URL</a></th>
                <th class="col-4"><a href="{{ Request::fullUrlWithQuery(['sortBy' => 'title', 'orderBy' => $orderBy]) }}">Page title</a></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($bookmarks as $bookmark)
                <tr class="d-flex">
                    <td class="col-2">{!! $bookmark->created_at !!}</td>
                    <td class="col-1"><img src="{!! $bookmark->favicon !!}" /></td>
                    <td class="col-5">{!! $bookmark->url !!}</td>
                    <td class="col-4"><a href="/bookmarks/{!! $bookmark->id !!}">{!! $bookmark->title !!}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-warning" role="alert">
            No bookmarks added yet
        </div>
    @endif

@endsection
