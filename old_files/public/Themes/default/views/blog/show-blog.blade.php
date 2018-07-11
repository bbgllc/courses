@extends('layouts.master')

@section('title', __('t.blog') . ' | ' . $post->title)

@section('description', $post->meta_description)
@section('after-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" />
    <style type="text/css">
        .post{
            
        }
        .post-meta {
            margin: 10px 0;
            font-size: 0.8em;
        }
        .post .title, .post .date, .post .comments, .post .views {
            font-weight: 400;
            color: #999;
            text-transform: capitalize;
        }
        .post .date {
            letter-spacing: 0.05em;
            font-weight: 400;
            text-transform: uppercase;
            color: #aaa;
        }
        .post .category a {
            color: #999;
            letter-spacing: 0.05em;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: none;
        }
        .post h3 {
            line-height: 1em;
            color: #222;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
            margin-bottom: 1rem;
        }
        .post p:not(.lead) {
            font-weight: 400;
            color: #777;
            font-size: 0.95em;
        }
        .post-footer {
            font-size: 0.8em;
        }
        .post .avatar {
            max-width: 40px;
            min-width: 40px;
            height: 40px;
            overflow: hidden;
            border-radius: 50%;
            margin-right: 10px;
        }
        .post .views::after {
            content: '|';
            display: inline-block;
            margin: 0 7px;
            font-size: 0.9em;
            color: #ccc;
        }
        .post .title::after, 
        .post .date::after, 
        .post .comments::after, 
        .post .views::after {
            content: '|';
            display: inline-block;
            margin: 0 7px;
            font-size: 0.9em;
            color: #ccc;
        }
        .post .title i, 
        .post .date i, 
        .post .comments i, 
        .post .views i {
            margin-right: 0px;
            font-size: 1.1em;
        }
        
        .post .title, .post .date, .post .comments, .post .views {
            font-weight: 400;
            color: #999;
            text-transform: capitalize;
        }
        i[class*="icon-"] {
            -webkit-transform: translateY(3px);
            transform: translateY(3px);
        }
        .widget {
            padding: 15px;
            border: 1px solid #eee;
        }
        .widget header {
            margin-bottom: 20px;
        }
        .widget.latest-posts a {
            display: block;
            color: #555;
            text-decoration: none;
        }
        .widget.latest-posts .item {
            margin-bottom: 20px;
        }
        .widget.latest-posts .image {
            min-width: 60px;
            max-width: 60px;
            height: 60px;
            overflow: hidden;
            margin-right: 20px;
        }
        .widget.latest-posts strong {
            font-size: 0.95em;
            display: block;
            line-height: 1em;
        }
        .widget.latest-posts .views, .widget.latest-posts .comments {
            font-size: 0.8em;
            font-weight: 400;
            color: #bbb;
            margin-top: 10px;
        }
        .widget.latest-posts .views i, .widget.latest-posts .comments i {
            margin-right: 5px;
        }
        .widget.latest-posts .views::after{
            content: '|';
            display: inline-block;
            margin: 0 7px;
            font-size: 0.9em;
            color: #ccc;
        }
        .post .meta-last:after{
            content: '';
        }
        
        .widget.search .form-group {
            position: relative;
        }
        .widget.search input {
            width: 100%;
            height: 50px;
            line-height: 40px;
            border: none;
            border-bottom: 1px solid #ddd;
            font-size: 0.95em;
            font-family: "Open Sans",sans-serif;
            font-weight: 400;
            background: none;
            padding: 0 25px 0 5px;
        }
        .widget.search input:focus,
        .widget.search .submit:focus{
            border: none;
            outline: none;
            background: #f5f5f5;
        }
        .widget.search .submit {
            height: 40px;
            padding: 5px 10px;
            line-height: 40px;
            background: none;
            border: none;
            color: #555;
            font-size: 0.9em;
            position: absolute;
            top: 0;
            right: 0;
        }
        
        .widget.tags .tag {
            padding: 5px 25px;
            border: 1px solid #ddd;
            margin: 15px 0;
            color: #777;
            font-size: 0.75em;
            text-transform: uppercase;
            font-weight: 600;
            text-decoration: none;
            border-radius: 50px;
            transition: background .3s;
        }
        
        .widget.tags .tag:hover {
            background: #999;
            color: #fff;
            border-color: #999;
        }

    </style>
@stop

@section('content')

    <!-- HOW IT WORKS -->
    <section class="pt-5 bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div id="course-list">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ $post->title }}</h5>
                            </div>
                            <div class="card-body">
                                {!! $post->body !!}
                            </div>
                            <div class="card-footer">
                                <span class="badge badge-secondary">
                                    <a href="{{ route('frontend.blog.categories', $post->category) }}" class="text-white">
                                        {{ $post->category->name }}
                                    </a>
                                </span>
                                <span class="text-muted pull-right">
                                    {{ __('t.last-updated') }}: {{ $post->updated_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                       
                    </div>
                    @if(env('DISQUS_ENABLED'))
                        <hr>
                        <div class="card card-body">
                            <div id="disqus_thread"></div>
                        </div>
                    @endif
                </div>  
                
                <aside class="col-md-4">
                    @include('blog.partials._sidebar')
                </aside>
            </div> 
        </div>
    </section>

@endsection

