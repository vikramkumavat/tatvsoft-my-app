<x-app-layout>
    <div class="container">

        <x-alert-message></x-alert-message>

        <x-dropdown-link href="{{ route('blog.create') }}"
        class="btn btn-primary">
            Add Blog
        </x-dropdown-link>

        <hr>

        <h2>Blog List</h2>
        <table class="table table-light">
            <thead>
                <tr>
                    <td>Title</td>
                    <td>Body</td>
                    <td width="35%">Image</td>
                    <td>Status</td>
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
                            <td>{{ $blog['active'] ? "Active" : "Disactive" }}</td>
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
