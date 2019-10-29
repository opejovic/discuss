<div class="text-gray-800 text-sm">
    {{ $reply->author->name }}: {{ $reply->body }} <span class="text-xs">{{ $reply->created_at->diffForHumans() }}</span>
</div>
