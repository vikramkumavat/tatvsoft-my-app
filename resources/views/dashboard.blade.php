<x-app-layout>
    <div class="container">

        <x-alert-message></x-alert-message>

        <h2>Create List</h2>
        <form action="{{ route('blog.create') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Blog Title</label>
                <input type="text" name="title" maxlength="250" class="form-control" id="title" placeholder="Enter Blog Title" autofocus>
            </div>
            <div class="form-group">
                <label for="body">Blog Body</label>
                <input type="text" name="body" maxlength="500" class="form-control" id="body" placeholder="Enter Blog Body">
            </div>
            <div class="form-group">
                <x-label for="date" :value="__('Start Date')" />

                <x-input id="date" class="form-control" type="text" name="date" :value="old('date')" required />
            </div>
            <div class="form-group">
                <x-label for="image" :value="__('Blog Image')" />

                <x-input id="image" class="block mt-1 w-full" type="file" name="image" :value="old('image')" />
            </div>
            <div class="form-group">
                <x-label for="blogactive" :value="__('Blog Active')" />

                <x-input id="blogactive" class="block mt-1" type="checkbox" name="blogactive" checked :value="old('blogactive')" />
            </div>
            <script>$('input[name="date"]').daterangepicker();</script>
            @if (Auth::user()->role)
                <div class="form-group">
                    <label for="Assign">Select User</label>
                    <select class="form-control" name="user_id" id="user_id">
                        <option value="">Select</option>
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
                    <td width="35%">Image</td>
                    <td>Owner</td>
                    <td width="18%">Action</td>
                </tr>
            </thead>

            <tbody>
                @if (count($blogs) > 0)
                    @foreach ($blogs as $blog)
                        <tr>
                            <td>{{ $blog['title'] }}</td>
                            <td>{{ $blog['body'] }}</td>
                            <td>
                                @php
                                    $image = !empty($blog['image']['url']) ? $blog['image']['url'] : 'noimage.jpg';
                                @endphp
                                <img src="{{ URL::to('/storage/images/'.$image) }}" alt="{{ $image }}"
                                width="35%">
                            </td>
                            <td>{{ $blog['first_name'] }}</td>
                            <td>
                                <div class="row">
                                <div class="col">
                                    <a href="blog/edit/{{ $blog['id'] }}" class="btn btn-primary">Edit</a>
                                </div>
                                <div class="col">
                                    <form method="POST" action="{{ route('blog.delete') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $blog['id'] }}">
                                        <input type="submit" class="btn btn-danger" value="Delete" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                    </form>
                                </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr style="text-align: center">
                        <td colspan="5">No Blog Found!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</x-app-layout>
