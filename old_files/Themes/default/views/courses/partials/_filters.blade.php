<!--
@if (count(array_intersect(array_keys(request()->query()), array_keys($mappings))))
    <li class="nav-item">
        <a class="nav-link active p-1 text-danger" href="{{ route('frontend.courses') }}">&times; Clear all filters</a>
    </li>
@endif
-->

@foreach ($mappings as $key => $map)
    @include('courses.partials._filter_list', compact('key', 'map'))
@endforeach
