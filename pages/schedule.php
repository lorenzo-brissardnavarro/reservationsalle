<?php

date_default_timezone_set("Europe/Paris");

$semaine = [];

$debut = strtotime("monday this week");

for ($i = 0; $i < 7; $i++) {
    array_push($semaine, date("Y-m-d", $debut));
    $debut = strtotime("+1 day", $debut);
}


$heures = [];

$debut = strtotime("today 08:00");
$fin   = strtotime("today 19:00");

while ($debut <= $fin) {
    array_push($heures, date("H:i", $debut));
    $debut = strtotime("+1 hour", $debut);
}



?>

<table>
  <tr>
    <td>&nbsp;</td>
    <?php
    foreach ($semaine as $jour) {
      echo '<td>' . date('l j F Y', strtotime($jour)) . '</td>';
    }
    ?>
  </tr>
  <?php
  foreach ($heures as $heure) {
    echo '<tr>
          <td>' . $heure . '</td>';
          foreach ($semaine as $jour) {
            $date = strtotime($jour . " " . $heure);
            $dateUrl = date("Y-m-d H:i:s", $date);
            if(date("N", $date) == 6 || date("N", $date) == 7){
              echo '<td>
                    Impossible
                    </td>';
            } else {
              echo '<td>
                  <a href="reservation-form.php?date=' . urlencode($dateUrl) . '">Réserver</a>
                  </td>';
            }
          }
    echo '</tr>';
  }
  ?>
</table>
