
<!--
<div class="widget search bg-light">
    
    <form action="{{ route('frontend.blog') }}" class="search-form">
        <div class="form-group">
            <input type="text" name="q" placeholder="{{__('t.what-are-you-looking-for')}}">
            <button type="submit" class="submit"><i class="icon-magnifier"></i></button>
        </div>
    </form>
</div>
<hr>
-->
<div class="widget latest-posts bg-light">
    <header>
        <h3 class="h6">{{__('t.latest-posts')}}</h3>
    </header>
    <div class="blog-posts">
        @foreach($posts as $p)
            <div class="media mb-2">
                <img class="d-flex mr-2" src="{{ $p->image }}" width="50" alt="">
                <div class="media-body">
                    <h6 class="mt-0 mb-0">
                        <a href="{{ route('frontend.blog.show', $p->slug) }}">{{ $p->title }}</a>
                    </h6>
                    <p style="font-size: .85em;">{{ str_limit($p->intro, 30) }}</p>
                </div>
            </div>

        @endforeach
        
    </div>
</div>
<hr>
<div class="widget tags bg-light">       
    <header>
        <h3 class="h6">{{__('t.categories')}}</h3>
    </header>
    <ul class="list-inline">
        @foreach($global_post_categories as $cat)
            <li class="list-inline-item mb-3">
                <a href="{{ route('frontend.blog.categories', $cat) }}" class="tag"> {{ $cat->name }}</a>
            </li>
        @endforeach
    </ul>
</div>