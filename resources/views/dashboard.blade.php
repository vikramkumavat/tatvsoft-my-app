<x-app-layout>
    <div class="container">

        <x-alert-message></x-alert-message>

        <x-slot name="header">
            <div class="jumbotron">
                <h1 class="display-4">You're logged in as {{ Auth::user()->role ? 'Admin' : "user" }}!</h1>
                <p class="lead"></p>
                <hr class="my-4">
              </div>
        </x-slot>

        <h2>Create List</h2>
        <form action="{{ route('blog.create') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="title">Blog Title</label>
                <input type="text" name="title" maxlength="250" class="form-control" id="title" placeholder="Enter Blog Title">
            </div>
            <div class="form-group">
                <label for="body">Blog Body</label>
                <input type="text" name="body" maxlength="500" class="form-control" id="body" placeholder="Enter Blog Body">
            </div>
            @if (Auth::user()->role)
                <div class="form-group">
                    <label for="Assign">Select User</label>
                    <select class="form-control" name="user_id" id="user_id">
                        @foreach ($users as $user)
                            <option value="{{ $user['id'] }}">{{ $user['first_name'].$user['last_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <hr>

        <h2>Blog List</h2>
        <table class="table table-light">
            <thead>
                <tr>
                    <td>Title</td>
                    <td>Body</td>
                    <td>Owner</td>
                    <td>Action</td>
                </tr>
            </thead>

            <tbody>
                @foreach ($blogs as $blog)
                    <tr>
                        <td>{{ $blog['title'] }}</td>
                        <td>{{ $blog['body'] }}</td>
                        <td>{{ $blog['first_name'] }}</td>
                        <td>
                            <a href="blog/edit/{{ $blog['id'] }}" class="badge badge-primary">Edit</a>

                            <form method="POST" action="{{ route('blog.delete') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $blog['id'] }}">
                                <input type="submit" class="btn btn-danger" value="Delete" onclick="event.preventDefault();
                                this.closest('form').submit();">
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
