<div class="text-justify text-gray-800 text-sm pb-1 flex items-center">
    <a href="{{ route('profile', $reply->author->name) }}" class="text-gray-600 underline">
        {{ $reply->author->name }}
    </a>: {{ $reply->body }} <span class="text-xs italic ml-2">{{ $reply->created_at->diffForHumans() }}</span>

    @can('delete', $reply)
    <form action="{{ route('replies.destroy', [$thread, $reply]) }}" method="POST">
        @method('DELETE')
        @csrf

        <button type="submit" class="focus:outline-none">
            <svg class="ml-1 w-3 h-3 text-red-600 fill-current hover:text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 13">
                <path d="M.7143 11.4286c0 .7857.6428 1.4285 1.4286 1.4285H7.857c.7858 0 1.4286-.6428 1.4286-1.4285V2.8571H.7143v8.5715zm1.7571-5.0857l1.0072-1.0072L5 6.85l1.5143-1.5143L7.5214 6.343 6.0071 7.857l1.5143 1.5143-1.0071 1.0072L5 8.8643l-1.5143 1.5143-1.0071-1.0072 1.5143-1.5143L2.4714 6.343zM7.5.7143L6.7857 0H3.2143L2.5.7143H0v1.4286h10V.7143H7.5z"/>
            </svg>
        </button>
    </form>
    @endcan
</div>

