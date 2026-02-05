<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/tools.php';


date_default_timezone_set("Europe/Paris");
$semaine = get_days();
$heures = get_hours();
$debutSemaine = date('Y-m-d 00:00:00', strtotime(min($semaine)));
$finSemaine = date('Y-m-d 23:59:59', strtotime(max($semaine)));
$events = get_all($pdo, $debutSemaine, $finSemaine);
foreach ($events as $event) {
    $event['start_ts'] = strtotime($event['start_date']);
    $event['end_ts']   = strtotime($event['end_date']);
}

?>

<table>
  <tr>
    <td>&nbsp;</td>
    <?php
    foreach ($semaine as $jour) {
      $timestamp = strtotime($jour);
      $formatter = new IntlDateFormatter('fr_FR',IntlDateFormatter::FULL,IntlDateFormatter::NONE);
      echo '<td>' . $formatter->format($timestamp) . '</td>';
    }
    ?>
  </tr>
  <?php
  foreach ($heures as $heure) {
    echo '<tr>
          <td>' . $heure . '</td>';
    foreach ($semaine as $jour) {
        $start_ts = strtotime($jour . ' ' . $heure);
        $end_ts   = strtotime('+1 hour', $start_ts);
        $event = event_taken($events, $start_ts, $end_ts);
        if ($event) {
          echo '<td class="slot taken">
                  <h3>' . htmlspecialchars($event['title']) . '</h3>
                  <p>' . htmlspecialchars($event['username']) . '</p>
                </td>';
        } elseif (date('N', $start_ts) >= 6) {
            echo '<td class="slot impossible"></td>';
        } else {
            echo '<td class="slot available">
                <a href="reservation-form.php?date=' . date('Y-m-d H:i:s', $start_ts) . '">
                    Réserver
                </a>
            </td>';
        }
    }
    echo '</tr>';
  }
  ?>
</table>


<?php include '../includes/footer.php'; ?>
