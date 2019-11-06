<div class="text-justify text-gray-800 text-sm pb-1">
    <a href="{{ route('profile', $reply->author->name) }}" class="text-gray-600 underline">
        {{ $reply->author->name }}
    </a>: {{ $reply->body }} <span class="text-xs italic">{{ $reply->created_at->diffForHumans() }}</span>
</div>
