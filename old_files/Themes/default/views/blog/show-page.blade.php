@extends('layouts.master')

@section('title', app_name() . ' | ' . $page->title)
@section('description', $page->meta_description)

@section('after-styles')
    
@stop

@section('content')

    <!-- HOW IT WORKS -->
    <section class="pt-5 bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $page->title }}</h5>
                        </div>
                        <div class="card-body">
                            {!! $page->body !!}
                        </div>
                        <div class="card-footer">
                            <span class="text-muted">
                                {{ __('t.last-updated') }}: {{ $page->updated_at->format('y-M-d') }}
                            </span>
                        </div>
                    </div>
                </div>  
                
                <aside class="col-md-4">
                    @include('blog.partials._page_sidebar')
                </aside>
            </div> 
        </div>
    </section>

@endsection

