<header class="header-wrapper bg-4D4D4D">
    <div class="container">

        <div class="header-container">
            <div class="row align-items-center">
                @php
                    $orgs=session()->get('organizations');
                    $s_org=session()->get('_org');
                @endphp
                <div class="col-xl-3 col-lg-4 col-md-5 col-sm-12 logo-col pt-3 pb-md-3">
                    <a href="{{route('home.menu')}}" class="d-inline-block logo-a mr-md-5 mr-3"><img
                            src="{{asset('assets/svg/logo.svg')}}" class="logo img-fluid logo-2"/></a>
                    <div class="dropdown d-inline-block">
                        <button class="btn lebanon-a btn-link dropdown-toggle p-0 m-0" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            <img src="{{$s_org->country_flag}}" class="flag img-fluid logo-image"/>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @if(count($orgs)>1)
                                @foreach($orgs as $org)
                                    @if($org->display==1)
                                        <a class="dropdown-item mb-2" onclick="return LangTranslate({{$org->id}})">
                                            <img src="{{$org->country_flag}}"/> {{$org->country}}
                                        </a>
                                    @endif
                                @endforeach
                            @else
                                <a class="dropdown-item mb-2" onclick="">
                                    <img src="{{$orgs[0]->country_flag}}"/> {{$orgs[0]->country}}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="cart-col text-right dropdown d-inline-block float-right d-md-none">
                        <a href="{{route('carts.show')}}" class="cart-link d-block dropdown-toggle"
                           id="dropdownMenuButton2">
                            <div class="CartItems"></div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 menu-col">
                    @php
                        $Skey=session()->get('skey');
                           $user=session()->get('user'.$Skey);
                           $full_name=@$user->details->FirstName.' '.@$user->details->LastName;
                           $id=@$user->details->ID;
                    @endphp
                    <nav class="nav main-nav justify-content-center">
                        <a class="nav-link {{ (\Request::route()->getName() == 'home.menu') ? 'active' : '' }}"
                           href="{{route('home.menu')}}">@lang('header_menu')</a>
                        <a class="nav-link {{\Request::segment(1)=='favorite'?'active':''}}"
                           href="{{route('favorite.items')}}">@lang('header_favourites')</a>
                        @php if(session()->get('is_login')){
                        echo '<a class="nav-link '.(\Request::segment(2)=='profile'?'active':'').'" href="'.route('customer.index').'">'.$full_name.'</a>';
                        }
                        else{
                        echo '<a class="nav-link '.(\Request::segment(1)=='login'?'active':'').'" href="'.route('auth.login').'">'.app('translator')->get('header_signin').'</a>';
                        }
                        @endphp

                    </nav>
                </div>
                <div class="col-xl-3 col-lg-2 col-md-1 cart-col text-right d-none d-md-block">
                    <div class="dropdown">
                        @php
                            $cart=(session()->has('cart') and session()->get('cart')!=null)?session()->get('cart'):null;
                        @endphp
                        @if(session()->has('is_login') and session()->get('is_login') == true)
                        <a @if($cart!=null) href="/checkout?step=Addresses" @endif id="ShoppingCart"
                           class="cart-link d-block checkout-address">
                            <div class="CartItems"></div>
                        </a>
                        @else
                            <a @if($cart!=null) href=/login" @endif id="ShoppingCart"
                               class="cart-link d-block checkout-address">
                                <div class="CartItems"></div>
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>
{{--      Start  Modal for choose the lang--}}
<div class="modal" id="LangTranModal" aria-hidden="true">
    <div class="modal-dialog lang-dialog">
        <div class="modal-content lang-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modelHeading"><b>@lang('choose_a_language')</b></h6>
            </div>
            <div class="modal-body">
                <input type="hidden" id="flagorgid" value="" name="flagorgid"/>
                <div class="lang-detail">
                    <div class="lang">
                        <a id="arlang">
                            <svg fill="#747474" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
    <g>
        <path d="M256,0C114.833,0,0,114.844,0,256s114.833,256,256,256s256-114.844,256-256S397.167,0,256,0z M357.771,264.969
			l-149.333,96c-1.75,1.135-3.771,1.698-5.771,1.698c-1.75,0-3.521-0.438-5.104-1.302C194.125,359.49,192,355.906,192,352V160
			c0-3.906,2.125-7.49,5.563-9.365c3.375-1.854,7.604-1.74,10.875,0.396l149.333,96c3.042,1.958,4.896,5.344,4.896,8.969
			S360.813,263.01,357.771,264.969z"/>
    </g>
</g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
</svg>
                            <span>العربية</span>
                        </a>
                    </div>
                    <div class="lang">
                        <a id="enlang">
                            <svg fill="#747474" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
    <g>
        <path d="M256,0C114.833,0,0,114.844,0,256s114.833,256,256,256s256-114.844,256-256S397.167,0,256,0z M357.771,264.969
			l-149.333,96c-1.75,1.135-3.771,1.698-5.771,1.698c-1.75,0-3.521-0.438-5.104-1.302C194.125,359.49,192,355.906,192,352V160
			c0-3.906,2.125-7.49,5.563-9.365c3.375-1.854,7.604-1.74,10.875,0.396l149.333,96c3.042,1.958,4.896,5.344,4.896,8.969
			S360.813,263.01,357.771,264.969z"/>
    </g>
</g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
</svg>
                            <span>English</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
{{--      End  Modal for choose the lang--}}
<script type="text/javascript">
    function LangTranslate(orgid) {
        $('#LangTranModal').modal();
        $('#flagorgid').val(orgid);
            {{--$('#arlang').attr('href', `{{route('switch.organization',['id'=>method_field('flagorgid'),'lang'=>'ar'])}}`);--}}
        var url = "{{route('switch.organization',['id'=>':orgid','lang'=>'ar'])}}";
        url = url.replace(':orgid', orgid);
        $('#arlang').attr('href', url);

        var enurl = "{{route('switch.organization',['id'=>':orgenid','lang'=>'en'])}}";
        enurl = enurl.replace(':orgenid', orgid);
        $('#enlang').attr('href', enurl);
    }
</script>
<style>
    #LangTranModal {
        padding-left: 0;
    }

    #LangTranModal .lang-dialog {
        height: 100%;
        max-width: 400px;
        margin: 0 auto;
    }

    #LangTranModal .lang-content {
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #f3f3f3;
        padding: 10px;
        border-radius: 0;
    }

    #LangTranModal .modal-header {
        display: flex;
        justify-content: center;
        border-bottom: none;
        color: #8dbf43;
        /*padding-bottom: 0*/
    }

    .lang-detail .lang:first-child {
        border-top: 1px solid #cccccc;
    }

    .lang-detail .lang:last-child {
        margin-bottom: 30px;
    }

    .lang-detail .lang {
        border-bottom: 1px solid #cccccc;
    }

    .lang-detail .lang svg {
        height: 24px;
        width: 24px;
    }

    .lang-detail .lang a {
        color: #808080;
        display: flex;
        align-items: center;
        padding: 15px 0;
    }

    .lang-detail .lang a span {
        margin: 0 15px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }

    @media screen and (max-width: 420px) {
        #LangTranModal .lang-dialog {
            max-width: 320px;
        }
    }
</style>
