<div class="text-justify text-gray-800 text-sm pb-1 flex">
    <div class="flex">
        <a href="{{ route('profile', $reply->author->name) }}" class="text-gray-600 underline">
            {{ $reply->author->name }}
        </a>: {{ $reply->body }}

        <span class="text-xs italic ml-2">{{ $reply->created_at->diffForHumans() }}</span>
    </div>

    @can('delete', $reply)
        <div class="flex items-center justify-between ml-1">

            <a href="{{ route('replies.edit', $reply) }}" class="block focus:outline-none">
                <svg class="w-3 h-3 text-gray-700 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34a.9959.9959 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                </svg>
            </a>


            <form action="{{ route('replies.destroy', $reply) }}" method="POST">
                @method('DELETE')
                @csrf

                <button type="submit" class="focus:outline-none">
                    <svg class="w-3 h-3 ml-1 text-gray-700 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 18">
                        <path d="M1 16c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V4H1v12zM14 1h-3.5l-1-1h-5l-1 1H0v2h14V1z" />
                    </svg>
                </button>
            </form>
        </div>
    @endcan

</div>

