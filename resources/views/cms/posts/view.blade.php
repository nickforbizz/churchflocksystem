@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Posts </h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Posts</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Index</a>
            </li>
        </ul>
    </div>
    <div class="row">


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Post Details</h4>
                        <div class="card-tools">
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-round">
                                <i class="flaticon-left-arrow-4 mr-2"></i>
                                Back
                            </a>
                            @can('edit posts')
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-round">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            @endcan
                            @can('delete posts')
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-round" onclick="return confirm('Are you sure you want to delete this post?');">
                                    <i class="fa fa-times"></i> Delete
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>


                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="text-primary">{{ $post->title }}</h2>
                                <p class="text-muted">
                                    <strong>Created By:</strong> {{ $post->user->name ?? 'N/A' }}
                                    <br>
                                    <strong>Created At:</strong> {{ $post->created_at->format('Y-m-d H:i') }}
                                </p>
                                <hr>
                            </div>
                        </div>
                        <!-- .row -->

                        @if($post->featured_img)
                        <div class="row mt-3">
                            <div class="col-md-12 text-center">
                                <h4>Featured Image</h4>
                                <img src="{{ asset('storage/' . $post->featured_img) }}" alt="{{ $post->title }}" class="img-fluid rounded" style="max-height: 300px; object-fit: cover;">
                            </div>
                        </div>
                        @endif


                        @if($post->content)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h4>Content</h4>
                                <div class="card-text bg-light p-3 rounded">
                                    {!! nl2br(e($post->content)) !!}
                                </div>
                            </div>
                        </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>

        @if($post->post_category)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Category Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Name:</strong> {{ $post->post_category->name }}</p>
                                @if($post->post_category->description)
                                <p><strong>Description:</strong> {{ $post->post_category->description }}</p>
                                @endif
                                <p><strong>Status:</strong> {!! $post->post_category->active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>' !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
    <!-- .page-inner -->

    @endsection


    @push('scripts')


    <script>
        $(document).ready(function() {



        });
    </script>

    @endpush