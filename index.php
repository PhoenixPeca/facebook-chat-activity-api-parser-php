<?php
error_reporting(0); // Disable all errors.
$shell = shell_exec("curl ");
$json = substr($shell, 10);

//print_r(json_decode($json));
//exit;

$batches = json_decode($json)->batches;
foreach($batches as $index=>$lookup_one) {
  if(!empty($lookup_one->ms)) {
    foreach($batches[$index]->ms as $index_two=>$lookup_two) {
      if(!empty($lookup_two->buddyList)) {
        //echo $index." ".$index_two;
        $buddylist = $batches[$index]->ms[$index_two]->buddyList;
        break;
      }
    }
  }
}
$new_sec = json_decode($json)->seq;
foreach($batches as $index=>$lookup_one) {
  if(!empty($lookup_one->seq)) {
    $new_sec = $batches[$index]->seq;
    break;
  }
}

if(empty($buddylist)) {
  header('Location: https://example.com/dev/?seq='.$new_sec);
  exit;
} else {
  //print_r($buddylist);
  echo '<html><head><meta http-equiv="refresh" content="10"></head><body>';
  echo '<a href="graph.php?g=enrique">Enrique T. Fresco II</a> - Last active '.time_elapsed_string('@'.$buddylist->{'100007401964159'}->lat).'<br>';
  echo '<a href="graph.php?g=ivan">Clark Ivan C. Taguran</a> - Last active '.time_elapsed_string('@'.$buddylist->{'100004240720455'}->lat).'<br>';
  echo '<a href="graph.php?g=keith">Keith Joshua S. Saile</a> - Last active '.time_elapsed_string('@'.$buddylist->{'100003124923298'}->lat).'<br>';
  echo '<a href="graph.php?g=ralph">Ralph Reymar L. Desquitado</a> - Last active '.time_elapsed_string('@'.$buddylist->{'100001229512802'}->lat).'<br>';
  echo '</body></html>';
  
  $enrique_on = ($buddylist->{'100007401964159'}->lat <= strtotime('-3 minutes'))?0:1;
  $enrique_of = ($buddylist->{'100007401964159'}->lat <= strtotime('-3 minutes'))?1:0;
  file_put_contents('enrique.txt', time().",$enrique_on,$enrique_of\n", FILE_APPEND);

  $ivan_on = ($buddylist->{'100004240720455'}->lat <= strtotime('-3 minutes'))?0:1;
  $ivan_of = ($buddylist->{'100004240720455'}->lat <= strtotime('-3 minutes'))?1:0;
  file_put_contents('ivan.txt', time().",$ivan_on,$ivan_of\n", FILE_APPEND);

  $keith_on = ($buddylist->{'100003124923298'}->lat <= strtotime('-3 minutes'))?0:1;
  $keith_of = ($buddylist->{'100003124923298'}->lat <= strtotime('-3 minutes'))?1:0;
  file_put_contents('keith.txt', time().",$keith_on,$keith_of\n", FILE_APPEND);

  $ralph_on = ($buddylist->{'100001229512802'}->lat <= strtotime('-3 minutes'))?0:1;
  $ralph_of = ($buddylist->{'100001229512802'}->lat <= strtotime('-3 minutes'))?1:0;
  file_put_contents('ralph.txt', time().",$ralph_on,$ralph_of\n", FILE_APPEND);
}


































function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}