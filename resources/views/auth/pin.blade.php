@extends('layouts.template')
@section('content')
    <div class="login-modal modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content col-sm-7 float-none p-0 mx-auto">
                <div class="modal-body">
                    <div class="form-group">
                        <label>PIN Code</label>
                        <input type="text" class="form-control" />
                    </div>
                    <div class="py-5">
                        <button type="button" class="btn btn-submit btn-login btn-block text-uppercase">Confirmation</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="{{asset('assets/js/jquery.matchHeight-min.js')}}"></script>
    <script type="text/javascript">

        jQuery(document).ready( function() {
            jQuery('#login-modal').modal();

        });

    </script>
@endsection