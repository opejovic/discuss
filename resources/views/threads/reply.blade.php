<div class="text-gray-800 text-sm">
    <a href="#" class="text-gray-600 underline">
        {{ $reply->author->name }}
    </a>: {{ $reply->body }} <span class="text-xs">{{ $reply->created_at->diffForHumans() }}</span>
</div>
