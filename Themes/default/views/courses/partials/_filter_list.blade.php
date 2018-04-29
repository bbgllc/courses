<div class="list-groupx mb-1"> 
    <div class="pl-2 pt-2 text-uppercase filter-title">
        {{ __('t.'.$key) }}
    </div>
    @foreach($map as $value => $name)
        <li class="nav-item">
            <a class="nav-link p-0 {{ request($key) === $value ? 'current' : '' }}" href="{{ route('frontend.courses', array_merge(request()->query(), [$key => $value, 'page' => 1])) }}">
                {{ $name }}
            </a>
        </li>
    @endforeach
    
    <!--
    @if(request($key))
        <li class="nav-item">
            <a class="nav-link current p-1 text-danger" href="{{ route('frontend.courses', array_except(request()->query(), [$key, 'page'])) }}"> &times; Clear this filter</a>
        </li>
    @endif
    -->
</div>