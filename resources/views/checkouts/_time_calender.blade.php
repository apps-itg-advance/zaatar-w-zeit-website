@php
    $output='';
     $now = strtotime($time);
     $open_time=strtotime($open_time);
     $close_time=strtotime($close_time);
     $eta=$eta*60;
     $selected='';
       if($date_selected=='today')
       {
         $selected='';
            $date=$current_date->format('Y-m-d');

          for( $i=strtotime('00:00:00'); $i<=$close_time+$eta; $i+=900) {
          if($schedule_date==$date." ".date("H:i",$i))
            {
                $selected='selected';
            }
            else{
            $selected='';
            }
          if($i>$now+$eta and $now >= $open_time)
          {
          $output .= "<option ".$selected." value='".$date." ".date("H:i",$i)."' >".date("H:i",$i)."</option>";
          }
          elseif($i>$open_time+$eta and $now < $open_time){
          $output .= "<option ".$selected." value='".$date." ".date("H:i",$i)."' >".date("H:i",$i)."</option>";
          }
          }
        }
        elseif($date_selected=='tomorrow')
        {
          $selected='';
            $date = date('Y-m-d',strtotime($current_date->format('Y-m-d')."+1 days"));
          for( $i=strtotime('00:00:00')+$eta; $i<=$close_time+$eta; $i+=900) {
          if($schedule_date==$date." ".date("H:i",$i))
            {
                $selected='selected';
            }
            else{
            $selected='';
            }
          if($i>=$open_time+$eta)
          {
             $output .= "<option  ".$selected." value='".$date." ".date("H:i",$i)."' >".date("H:i",$i)."</option>";
          }
          }
        }
@endphp
<label class="{{(App::isLocale('ar') ? 'ar-custom-label' : 'label-l')}}">
    <select class="select-l" id="ScheduleDate" name="schedule_date">
        {!! $output !!}
    </select>
</label>

<!-- Custom select structure -->

