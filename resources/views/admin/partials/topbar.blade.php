<div class="row">
    <div class="col-lg-8 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">@yield('page-title', 'Dashboard')</h5>
                        <p class="mb-4">@yield('page-description', 'Hoş geldiniz, ' . auth()->user()->name . '!')</p>
                        
                        @if(isset($breadcrumb))
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                @foreach($breadcrumb as $item)
                                    @if($loop->last)
                                        <li class="breadcrumb-item active">{{ $item['title'] }}</li>
                                    @else
                                        <li class="breadcrumb-item">
                                            <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        </nav>
                        @endif
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('sneat/assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>