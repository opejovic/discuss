<div class="sm:w-1/2 pt-2 w-full pt-5">
    <form action="{{ route('replies.store', [$thread->channel, $thread]) }}" method="POST">
        @csrf
        <div class="pb-1">
            <textarea class="@error('body') border-red-500 @enderror w-full text-gray-700 placeholder-gray-500 text-sm border border-gray-200 p-3 rounded-lg focus:outline-none" name="body" id="" rows="4" placeholder="Have something to say?"></textarea>

            @error('body')
                <span class="text-red-500 text-xs" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <button type="submit" class="rounded-lg shadow bg-gray-200 hover:bg-gray-300 w-full sm:block px-4 py-2 uppercase text-gray-700 text-xs">Submit</button>
    </form>
</div>
