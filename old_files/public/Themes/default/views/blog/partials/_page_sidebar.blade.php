<div class="card mb-4">
    <div class="card-body">
        <ul class="fa-ul">
            @foreach($footer_pages->sortBy('title') as $p)
                <li>
                    <i class="fa-li fa fa-angle-right text-secondary"></i>
                    <a href="{{ route('frontend.page.show', $p->slug) }}" class="{{ $p->id != $page->id ? 'text-success' : '' }}">
                        {{ $p->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<!--
<div class="card bg-secondary">
    <div class="card-body">
        Place ads here
    </div>
</div>
-->