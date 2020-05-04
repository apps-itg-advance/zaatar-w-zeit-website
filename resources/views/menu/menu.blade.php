@extends('layouts.template')
@section('content')

    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-favourite-items">
        <div class="title-div mb-4 pb-2 text-center">
            <h2 class="title text-8DBF43">{{$_cat_title}}</h2>
        </div>

        <div class="col-lg-12 float-none p-0 mx-auto">
            @include('menu._menu_grid',array('display_favourite'=>false))
        </div>

    </div>
    @include('partials.cart')
@endsection

@section('javascript')
  <script src="{{asset('assets/js/blazy.min.js')}}"></script>
  <script>
      (function() {
          // Initialize
          var bLazy = new Blazy();
      })();
  </script>
@include('menu._menu_js')
@endsection
@section('css')
    <style type="text/css">
        .pointer{
            cursor: pointer;
        }
    </style>
@endsection