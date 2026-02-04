<?php

include '../includes/tools.php';

$semaine = get_days();
$heures = get_hours();



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
