@component('profiles.activities.activity')
    @slot('heading')
        <div class="text-xs text-gray-500">Published a thread on {{ $activity->subject->published_at }}</div>
        <a href="{{ route('threads.show', [$activity->subject->channel, $activity->subject->id]) }}">
            {{ $activity->subject->title }}
        </a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
