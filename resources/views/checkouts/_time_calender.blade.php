@php
    $output='';
     $now = strtotime($time);
     $open_time=strtotime($open_time);
     $close_time=strtotime($close_time);
     $eta=$eta*60;
       if($date_selected=='today')
       {
            $date=$current_date->format('Y-m-d');
          for( $i=strtotime('00:00:00'); $i<=$close_time; $i+=900) {
          if($i>$now+$eta and $now >= $open_time)
          {
          $output .= "<option value='".$date." ".date("H:i",$i)."' >".date("H:i",$i)."</option>";
          }
          elseif($i>$open_time+$eta and $now < $open_time){
          $output .= "<option value='".$date." ".date("H:i",$i)."' >".date("H:i",$i)."</option>";
          }

          }
        }
        elseif($date_selected=='tomorrow')
        {
            $date = date('Y-m-d',strtotime($current_date->format('Y-m-d')."+1 days"));
          for( $i=strtotime('00:00:00')+$eta; $i<=$close_time; $i+=900) {
          if($i>=$open_time)
          {
             $output .= "<option value='".$date." ".date("H:i",$i)."' >".date("H:i",$i)."</option>";
          }
          }
        }
@endphp
<label class="label-l">
    <select class="select-l" id="ScheduleDate" name="schedule_date">
        {!! $output !!}
    </select>
</label>

<!-- Custom select structure -->

