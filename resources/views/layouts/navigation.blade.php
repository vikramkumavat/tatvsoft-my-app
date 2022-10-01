<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" {{ route('dashboard') }}>Logo</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse pl-3" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard<span class="sr-only">(current)</span></a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li> --}}
        </ul>
    </div>

    <div class="dropdown pr-5 mr-3">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ Auth::user()->first_name }}
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </div>
      </div>
</nav>
