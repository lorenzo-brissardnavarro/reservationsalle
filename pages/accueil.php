<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/tools.php';

$massages = get_all_services($pdo);

?>

<h2>Bienvenue au Salon de Massage Zenitude 🌿</h2>
<p>Découvrez nos massages relaxants et réservez facilement votre créneau en ligne.</p>

<section class="massages">
    <?php 
    foreach($massages as $massage){
        echo '
        <div class="massage-card">
            <img src="../images/' . $massage['image'] . '" alt="' . htmlspecialchars($massage['name']) . '">
            <h3>' . htmlspecialchars($massage['name']) . '</h3>';
            if($massage['duration'] > 1){
                echo '<p>Durée : ' . $massage['duration'] . ' heures</p>';
            } else{
                echo '<p>Durée : ' . $massage['duration'] . ' heure</p>';
            }
        echo '</div>';
    }
    if(empty($_SESSION['id'])){
        echo '<p>Pour réserver, veuillez vous <a href="signin.php">connecter</a> ou <a href="signup.php">créer un compte</a>.</p>';
    } else {
        echo '<p>Pour réserver un massage, accédez directement au <a href="schedule.php">planning</a>.</p>';
    }
    ?>
</section>


<?php include '../includes/footer.php'; ?>