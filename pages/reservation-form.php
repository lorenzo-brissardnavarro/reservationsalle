<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/tools.php';

// if (!isset($_SESSION['id'])) {
//     header("Location: ../index.php");
//     exit;
// }

if(isset($_GET['date'])){
    $creneau = $_GET['date'];
}
$jour_choisi = date("Y-m-d", strtotime($creneau));
$heure_choisie = date("H:i", strtotime($creneau));
$semaine = get_days();
$heures = get_hours();


$massages = get_all_services($pdo);
?>


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
    <form action="" method="post">
        <label for="service">Choix de la prestation</label>
        <select name="service" id="service">
            <?php
            if(!isset($_POST['service']) || $_POST['service'] === 'libre'){
                echo '<option value="libre" selected>Prestation libre</option>';
            } else{
                echo '<option value="libre">Prestation libre</option>';
            }
            foreach ($massages as $massage) {
                if(isset($_POST['service']) && $_POST['service'] === $massage['name']){
                     echo "<option value=\"".htmlspecialchars($massage["name"])."\" selected>".htmlspecialchars($massage["name"])."</option>";
                } else{
                    echo "<option value=\"".htmlspecialchars($massage["name"])."\">".htmlspecialchars($massage["name"])."</option>";
                }
            }
            ?>
        <input type="submit" name="submit_prestation" value="Choisir cette prestation">
    </form>
    <?php
    if(!empty($_POST["submit_prestation"])){
        if($_POST['service'] === "libre"){
            echo '<form action="" method="POST">
            <label for="title">Titre</label>
            <input type="text" name="title" id="title" placeholder="Titre de la réservation">
            <label for="debut">Heure de début :</label>
            <select name="debut" id="debut">';
                for ($i=0; $i < count($heures); $i++) {
                    if($heures[$i] == $heure_choisie){
                        echo "<option value=".$heures[$i]." selected>".$heures[$i]."</option>";
                    } else{
                        echo "<option value=".$heures[$i].">".$heures[$i]."</option>";
                    }
                }
            echo '</select>
            <label for="fin">Heure de fin :</label>
            <select name="fin" id="fin">';
                for ($i=1; $i < count($heures); $i++) {
                    if(strtotime($heures[$i]) == strtotime($heure_choisie . "+1 hour")){
                        echo "<option value=".$heures[$i]." selected>".$heures[$i]."</option>";
                    } else{
                        echo "<option value=".$heures[$i].">".$heures[$i]."</option>";
                    }
                }
            echo '</select>
            <label for="jour">Date:</label>
            <input type="date" id="jour" name="jour" value="'.$jour_choisi.'" min="'.$semaine[0].'" max="'.$semaine[4].'" />
            <label for="description">Description</label>
            <textarea id="description" name="description" maxlength="450" rows="5" cols="33"></textarea>
            <input type="submit" name="submit" value="Soumettre ma réservation">
        </form>';
        } else {
        echo "<p>Test</p>";
        }
    }
    ?>
</section>