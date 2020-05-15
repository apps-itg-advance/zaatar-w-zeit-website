<header class="header-wrapper bg-4D4D4D" >
    <div class="container">

        <div class="header-container">
            <div class="row align-items-center">
                @php
                    $orgs=session()->get('organizations');
                    $s_org=session()->get('_org');
                @endphp

                <div class="col-xl-3 col-lg-4 col-md-5 col-sm-12 logo-col pt-3 pb-md-3">
                    <a href="{{route('home.menu')}}" class="d-inline-block logo-a mr-md-5 mr-3"><img src="{{asset('assets/svg/logo.svg')}}" class="logo img-fluid logo-2" /></a>
                    <div class="dropdown d-inline-block">
                        <button class="btn lebanon-a btn-link dropdown-toggle p-0 m-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{$s_org->country_flag}}" class="flag img-fluid logo-image" />
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                            @if(count($orgs)>1)
                            @foreach($orgs as $org)
                              @if($org->display==1)
                            <a class="dropdown-item mb-2" href="{{route('switch.organization',['id'=>$org->id])}}">
                                <img src="{{$org->country_flag}}" /> {{$org->country}}
                            </a>
                                    @endif
                            @endforeach
                            @else
                                <a class="dropdown-item mb-2" href="#">
                                    <img src="{{$orgs[0]->country_flag}}" /> {{$orgs[0]->country}}
                                </a>
                            @endif

                        </div>
                    </div>
                    <div class="cart-col text-right dropdown d-inline-block float-right d-md-none">
                        <a href="{{route('carts.show')}}" class="cart-link d-block dropdown-toggle" id="dropdownMenuButton2">
                            <div class="CartItems"></div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 menu-col">
                    @php
                        $Skey=session()->get('skey');
                           $user=session()->get('user'.$Skey);
                           $full_name=@$user->details->FirstName.' '.@$user->details->LastName;
                    @endphp
                    <nav class="nav main-nav justify-content-center">
                        <a class="nav-link {{ (\Request::route()->getName() == 'home.menu') ? 'active' : '' }}" href="{{route('home.menu')}}">Menu</a>
                        {{--<a class="nav-link {{@$this->favourite_active}}" href="{{route('customer.favourites')}}">Favourites</a>--}}
                        <a class="nav-link {{\Request::segment(2)=='favourites'?'active':''}}" href="{{route('customer.favourite.items')}}">Favourites</a>
                        @php if(session()->get('is_login')){
                        //echo '<a class="nav-link '.@$this->profile_active.'" href="'.route('customer.index').'">Profile</a>';
                        echo '<a class="nav-link '.(\Request::segment(2)=='profile'?'active':'').'" href="'.route('customer.index').'">'.$full_name.'</a>';
                        }
                        else{
                        //echo '<a class="nav-link '.@$this->login_active.'" href="'.route('auth.login').'">Sign In</a>';
                        echo '<a class="nav-link '.(\Request::segment(1)=='login'?'active':'').'" href="'.route('auth.login').'">Sign In</a>';
                        }
                        @endphp

                    </nav>
                </div>
                <div class="col-xl-3 col-lg-2 col-md-1 cart-col text-right d-none d-md-block">
                    <div class="dropdown">
                        <a href="{{route('checkout.address')}}" class="cart-link d-block checkout-address">
                            <div class="CartItems"></div>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</header>