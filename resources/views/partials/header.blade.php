<header class="header-wrapper bg-4D4D4D">
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
                            <a class="dropdown-item mb-2" href="{{route('switch.organization',['id'=>$org->id])}}">
                                <img src="{{$org->country_flag}}" /> {{$org->country}}
                            </a>
                            @endforeach
                            @else
                                <a class="dropdown-item mb-2" href="#">
                                    <img src="{{$orgs[0]->country_flag}}" /> {{$orgs[0]->country}}
                                </a>
                            @endif

                        </div>
                    </div>
                    <div class="cart-col text-right dropdown d-inline-block float-right d-md-none">
                        <a href="#" class="cart-link d-block dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            20
                        </a>
                        <div class="dropdown-menu dropdown-menu-right cart-dropdown" aria-labelledby="dropdownMenuButton2">
                            <h4 class="title text-center">Order Summary</h4>
                            <h5 class="user">JOEY ZALOUM</h5>
                            <div class="cart-items my-5">
                                <div class="cart-item mb-4">
                                    <h5 class="name text-4D4D4D">Famous Chicken<span class="price d-inline-block ml-3">11,250</span></h5>
                                    <div class="info text-808080">
                                        Oat bread, remove pickles, Add-on fries, Add turkey, thin bread, toasted, Cut in half
                                    </div>
                                    <div class="cart-btns">
                                        <a href="#"><img src="{{asset('assets/images/icon-cart-edit.png')}}" /></a>
                                        <a href="#"><img src="{{asset('assets/images/icon-cart-add.png')}}" /></a>
                                        <a href="#"><img src="{{asset('assets/images/icon-cart-delete.png')}}" /></a>
                                    </div>
                                </div>
                                <div class="cart-item mb-4">
                                    <h5 class="name text-4D4D4D">Famous Chicken<span class="price d-inline-block ml-3">11,250</span></h5>
                                    <div class="info text-808080">
                                        Oat bread, remove pickles, Add-on fries, Add turkey, thin bread, toasted, Cut in half
                                    </div>
                                    <div class="cart-btns">
                                        <a href="#"><img src="{{asset('assets/images/icon-cart-edit.png')}}" /></a>
                                        <a href="#"><img src="{{asset('assets/images/icon-cart-add.png')}}" /></a>
                                        <a href="#"><img src="{{asset('assets/images/icon-cart-delete.png')}}" /></a>
                                    </div>
                                </div>
                                <div class="cart-item mb-4">
                                    <h5 class="name text-4D4D4D">Famous Chicken<span class="price d-inline-block ml-3">11,250</span></h5>
                                    <div class="cart-btns">
                                        <a href="#"><img src="{{asset('assets/images/icon-cart-edit.png')}}" /></a>
                                        <a href="#"><img src="{{asset('assets/images/icon-cart-add.png')}}" /></a>
                                        <a href="#"><img src="{{asset('assets/images/icon-cart-delete.png')}}" /></a>
                                    </div>
                                    <div class="speacial-meal bg-8DBF43">
                                        MEAL <span class="d-inline-block mx-3">Side Fries + Soft drink</span><span class="d-inline-block">5,000</span>
                                        <a href="#" class="close"><img src="{{asset('assets/images/icon-close-white.png')}}" /></a>
                                    </div>
                                </div>
                            </div>
                            <div class="carttotal-block mt-3">
                                <div class="delivery-fee text-right"><span class="float-left">Delivery fee</span> 2,000 LL</div>
                                <hr/>
                                <div class="total-fee text-right"><span class="float-left">Total</span> 30,000 LL</div>
                            </div>
                            <div class="action-buttons text-center mt-5 mb-3">
                                <button class="btn btn-B3B3B3 text-uppercase">Clear All</button>
                                <button class="btn btn-8DBF43 text-uppercase">Checkout</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 menu-col">
                    <nav class="nav main-nav justify-content-center">
                        <a class="nav-link {{ (\Request::route()->getName() == 'home.menu') ? 'active' : '' }}" href="{{route('home.menu')}}">Menu</a>
                        {{--<a class="nav-link {{@$this->favourite_active}}" href="{{route('customer.favourites')}}">Favourites</a>--}}
                        <a class="nav-link {{\Request::segment(2)=='favourites'?'active':''}}" href="{{route('customer.favourite.items')}}">Favourites</a>
                        @php if(session()->get('is_login')){
                        //echo '<a class="nav-link '.@$this->profile_active.'" href="'.route('customer.index').'">Profile</a>';
                        echo '<a class="nav-link '.(\Request::segment(2)=='profile'?'active':'').'" href="'.route('customer.index').'">Profile</a>';
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
                        <a href="{{route('home.menu')}}" class="cart-link d-block">
                            <div id="CartItems"></div>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</header>