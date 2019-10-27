@foreach ($threads as $thread)
    <div>
        <a href="{{ $thread->path() }}">
            {{ $thread->title }}
        </a>
    </div>
@endforeach