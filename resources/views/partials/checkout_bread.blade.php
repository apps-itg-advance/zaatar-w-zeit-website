<div class="checkout-navblock mb-5">
    <ul class="checkout-nav">
        @foreach($steps as $step)
            <li class="{{(request()->get('step') == $step->ArrayName || in_array($step->ArrayName,$selectedSteps)) ?'current active':''}}">
                <a href="{{(request()->get('step') == $step->ArrayName || in_array($step->ArrayName,$selectedSteps))?route('checkout.index',['step' => $step->ArrayName]):'#'}}">
                    {{$step->Label}}
                </a>
            </li>
        @endforeach
    </ul>
</div>
