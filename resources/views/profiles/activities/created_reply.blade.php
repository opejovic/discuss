@component('profiles.activities.activity')
    @slot('heading')
        <div class="text-xs text-gray-500">Left a reply {{ $activity->subject->created_at->format('d M Y') }}</div>
        <a href="{{ route('threads.show', [$activity->subject->thread->channel, $activity->subject->thread]) }}">
            {{ $activity->subject->thread->title }}
        </a>
    @endslot

    @slot('body')
        {!! nl2br($activity->subject->body) !!}
    @endslot
@endcomponent
