<x-app-layout>
    <div class="container">
        <h2>Update List</h2>
        <form action="{{ route('blog.update') }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $blog->id }}">
            <div class="form-group">
                <label for="title">Blog Title</label>
                <input type="text" name="title" maxlength="250" class="form-control" id="title" value="{{ $blog->title }}" placeholder="Enter Blog Title">
            </div>
            <div class="form-group">
                <label for="body">Blog Body</label>
                <input type="text" name="body" maxlength="500" class="form-control" id="body" value="{{ $blog->body }}" placeholder="Enter Blog Body">
            </div>
            @if (Auth::user()->role)
                <div class="form-group">
                    <label for="Assign">Select User</label>
                    <select class="form-control" name="user_id" id="user_id">
                        @foreach ($users as $user)
                            <option value="{{ $user['id'] }}"
                            {{ ($blog['usder_id'] == $users) ? "selected" : "" }}
                            >{{ $user['first_name'].$user['last_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

    </div>
</x-app-layout>
