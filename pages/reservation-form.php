<?php

include '../includes/tools.php';

if(isset($_GET['date'])){
    $creneau = $_GET['date'];
}
$jour_choisi = date("Y-m-d", strtotime($creneau));
$heure_choisie = date("H:i", strtotime($creneau));
$semaine = get_days();
$heures = get_hours();



?>


<main>
    <section class="container-form">
        <article class="auth-header">
            <i class="auth-icon fa-solid fa-user-plus"></i>
            <h1>Formulaire de réservation</h1>
            <p class="subtitle">Utilisateur : </p>
        </article>
        <?php 
        if (!empty($error)){
            echo '<p class="form-error">' . $error .  '</p>';
        }
        ?>
        <form action="" method="POST">
            <label for="title">Titre</label>
            <input type="text" name="title" id="title" placeholder="Titre de la réservation">
            <label for="debut">Heure de début :</label>
            <select name="debut" id="debut">
                <?php 
                for ($i=0; $i < count($heures); $i++) {
                    if($heures[$i] == $heure_choisie){
                        echo "<option value=".$heures[$i]." selected>".$heures[$i]."</option>";
                    } else{
                        echo "<option value=".$heures[$i].">".$heures[$i]."</option>";
                    }
                }
                ?>
            </select>
            <label for="fin">Heure de fin :</label>
            <select name="fin" id="fin">
                <?php 
                for ($i=1; $i < count($heures); $i++) {
                    if(strtotime($heures[$i]) > strtotime($heure_choisie)){
                        echo "<option value=".$heures[$i].">".$heures[$i]."</option>";
                    }
                }
                ?>
            </select>
            <label for="jour">Date:</label>
            <input
            type="date"
            id="jour"
            name="jour"
            value="<?php echo $jour_choisi ?>"
            min="<?php echo $semaine[0] ?>"
            max="<?php echo $semaine[4] ?>" />
            <label for="description">Description</label>
            <textarea id="description" name="description" maxlength="450" rows="5" cols="33"></textarea>
            <input type="submit" value="Soumettre ma réservation">
        </form>
    </section>
</main>