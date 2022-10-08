<x-app-layout>
    <div class="container">

        <x-alert-message></x-alert-message>

        <x-dropdown-link href="{{ route('dashboard') }}"
        class="btn btn-info">
            Back
        </x-dropdown-link>
        <hr>
        <h2>Create List</h2>
        <form action="{{ route('blog.store') }}" method="post" name="blogform" id="blogform" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $blog->id??'' }}">
            <div class="form-group">
                <label for="title">Blog Title</label>
                <input type="text" id="title" name="title" maxlength="250" class="form-control" id="title" value="{{ $blog->title??'' }}" placeholder="Enter Blog Title" autofocus>
            </div>
            <div class="form-group">
                <label for="body">Blog Body</label>
                <input type="text" id="body" name="body" maxlength="500" class="form-control" id="body" value="{{ $blog->body??'' }}" placeholder="Enter Blog Body">
            </div>
            <div class="form-group">
                <?php
                    $stDate = isset($blog->start_date) ? date("m/d/Y", strtotime($blog->start_date)) : '';
                    $endDate = isset($blog->end_date) ? date("m/d/Y", strtotime($blog->end_date)) : '';
                ?>
                <x-label for="date" :value="__('Start Date')" />
                <x-input id="date" class="form-control" type="text" name="date" :value="(isset($stDate) && isset($endDate)) ? ($stDate .' - '. $endDate) :old('date')" required />
            </div>
            <div class="form-group">
                @if (!isset($blog))
                    <x-label for="image" :value="__('Blog Image')" />
                @endif
                <x-input id="image" class="block mt-1 w-full" type="file" name="image" :value="isset($blog->image->url)?$blog->image->url:old('image')" />
                @if (isset($blog) && !empty($blog))
                    @php
                        $image = (isset($blog->image->url) && !empty($blog->image->url))
                        ? $blog->image->url :
                        'noimage.jpg';
                    @endphp
                    Uploaded Image
                    <img src="{{ URL::to('/storage/images/'.$image) }}" alt="{{ $image }}" width="15%">
                @endif
            </div>
            <div class="form-group">
                <x-label for="blogactive" :value="__('Blog Active')" />
                <x-input id="blogactive" class="block mt-1" type="checkbox" name="blogactive" :value="old('blogactive')" :checked="isset($blog->active) && empty($blog->active)?'':'checked'" />
            </div>
            <script>$('input[name="date"]').daterangepicker();</script>
            @if (Auth::user()->role)
                <div class="form-group">
                    <label for="Assign">Select User</label>
                    <select class="form-control" name="user_id" id="user_id">
                        <option value="">Select</option>
                        @foreach ($users as $user)
                            <option value="{{ $user['id'] }}" {{ isset($blog->user_id) ? (($blog->user_id == $user['id'])?'selected':'') : '' }}>{{ $user['first_name'].$user['last_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <script>
            $(function(){
                $("#blogform").validate({
                    rules: {
                        title: "required",
                        body: "required",
                        date: "required",
                        image: {
                            required: true,
                            filesize : 1,
                        },
                    },
                    messages: {
                        title: "This blog title is required.",
                        body: "This blog body is required.",
                        date: "This blog date is required.",
                        image: {
                            required: "This blog is required.",
                        }
                    }

                });

            });
        </script>
    </div>

    <br>
    <br>
    <br>
</x-app-layout>
