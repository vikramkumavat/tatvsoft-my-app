<x-app-layout>
    <div class="container py-4">
        <x-alert-message></x-alert-message>
        <h2>Blog list</h2>
        <hr>
        @if (count($blogs) > 0)
            @foreach ($blogs as $blog)
            <div class="card">
                @php
                    $image = !empty($blog['image']['url']) ? $blog['image']['url'] : 'noimage.jpg';
                @endphp
                <img src="{{ URL::to('/storage/images/'.$image) }}" alt="{{ $image }}" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">{{ $blog['title'] }}</h5>
                    <p class="card-text">{{ $blog['body'] }}</p>
                    <small>
                        created by {{ $blog['user']['first_name'] }} {{ \Carbon\Carbon::createFromTimeStamp(strtotime($blog['created_at']))->diffForHumans() }}
                    </small>
                </div>
            </div>
            <br><br>
            @endforeach
        @else
            <p class="text-center"> No Blog found..! </p>
        @endif
    </div>
</x-app-layout>
