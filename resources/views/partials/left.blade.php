<div class="col-xl-2 col-lg-3 col-md-12 col-sm-12 col-items aside-menu left-menu-hide-mobile">
    <div class="items-container" id="items-container">
        @php
            $this->_org=session()->get('_org');
        @endphp
        @if (session()->has('navigations_'.$this->_org->id))
            @foreach(session()->get('navigations_'.$this->_org->id) as $nav)
                @php
                    $cat_id=!isset($cat_id) ? 0 : $cat_id;
                    $array_name=json_decode($nav->Label,true);
                        if(is_array($array_name) and isset($array_name['en']))
                        {
                            $url_name=str_replace('-',' ',$array_name['en']);
                        }
                        elseif(is_array($array_name) and !isset($array_name['en']))
                        {
                        $url_name=str_replace('-',' ',$array_name[0]);
                        }
                        else{
                        $url_name=str_replace(' ','-',$nav->Label);
                        }
                @endphp
                <a href="{{route('home.menu', ['id'=>$nav->ID,'name'=>$url_name])}}">
                    <div class="media @if($nav->ID==$cat_id) active  @endif">
                        <img src="{{$nav->URL}}" style="!important; border-radius: 50%; width: 50px" class="mr-3"
                             alt="...">
                        <div class="align-self-center media-body">
                            <h5 class="mt-0">
                                {{strtoupper($nav->Label)}}
                            </h5>
                        </div>
                    </div>
                </a>
            @endforeach
        @endif
    </div>
</div>
