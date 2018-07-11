<div class="post col-md-6 col-sm-12 mb-4">
    <div class="bg-light clearfix pb-2">
        <div class="post-thumbnail">
            <a href="{{ route('frontend.blog.show', $post->slug) }}">
                <img src="{{ $post->image }}" alt="..." class="img-fluid">
            </a>
        </div>
        <div class="post-details p-4">
            <div class="post-meta d-flex justify-content-between">
                <div class="date meta-last">
                    {{ $post->created_at->format('d F | Y') }}
                </div>
                <div class="category">
                    <a href="{{ route('frontend.blog.categories', $post->category) }}">{{ $post->category->name }}</a>
                </div>
            </div>
            
            <a href="{{ route('frontend.blog.show', $post->slug) }}">
                <h3 class="h4">{{ $post->title }}</h3>
            </a>
            <p class="text-muted">
                {{ $post->intro }}
            </p>
            <footer class="post-footer d-flex align-items-center">
                <a href="#" class="author d-flex align-items-center flex-wrap">
                    <div class="avatar">
                        <img src="{{ $post->author->picture }}" alt="" class="img-fluid">
                    </div>
                    <div class="title"><span>{{ $post->author->name }}</span></div>
                </a>
                <div class="date meta-last"> 
                    <i class="icon-clock"></i> {{ $post->created_at->diffForHumans() }}
                </div>
                <!--
                <div class="comments meta-last">
                    <i class="icon-bubbles"></i> 12
                </div>
                -->
            </footer>
        </div>
    </div>
</div>