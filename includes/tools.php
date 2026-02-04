<?php

// Vérification si les champs du formulaire sont bien remplis
function empty_fields($post, $fields) {
    foreach ($fields as $field) {
        if (empty($post[$field])) {
            return true;
        }
    }
    return false;
}



// Vérification validité données inscription utilisateur
function field_verification($username, $password, $confirm_password){
    if (strlen($username) < 3 || strlen($username) > 255) {
        return "Le login doit contenir entre 3 et 255 caractères";
    }
    elseif (strlen($password) < 6 || !preg_match('/[0-9]/', $password)) {
        return "Le mot de passe doit contenir au moins 6 caractères et au moins un chiffre";
    }
    elseif ($password != $confirm_password) {
        return "Les deux mots de passe doivent être égaux";
    }
    return true;
}



// Récupération des informations d'un utilisateur
function user_by_username($pdo, $username) {
    $sql = "SELECT * FROM user WHERE username = :username";
    $query = $pdo->prepare($sql);
    $query->execute([':username' => $username]);
    return $query->fetch(PDO::FETCH_ASSOC);
}



// Vérification si username existe déjà dans la BDD
function username_exists($pdo, $username, $id = null) {
    if ($id === null) {
        $sql = "SELECT id FROM user WHERE username = :username";
        $params = [':username' => $username];
    } else {
        $sql = "SELECT id FROM user WHERE username = :username AND id != :id";
        $params = [':username' => $username, ':id' => $id];
    }
    $query = $pdo->prepare($sql);
    $query->execute($params);
    if ($query->fetch(PDO::FETCH_ASSOC)) {
        return true;
    }
    return false;
}



// Fonction pour enregistrer les données d'inscription de l'utilisateur dans la BDD
function recording($pdo, $username, $password) {
    if (username_exists($pdo, $username)) {
        return "Ce nom d'utilisateur est déjà utilisé";
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO user (username, password) VALUES (:username, :password)";
    $query = $pdo->prepare($sql);
    $query->execute([':username' => $username, ':password' => $hash]);
    return true;
}



// Processus d'appel pour vérification puis enregistrement inscription dans BDD
function sign_up($pdo, $post) {
    if (empty_fields($post, ["username", "password", "confirm_password"])) {
        return "Veuillez remplir l'ensemble des champs.";
    }
    $result = field_verification(trim($post["username"]), $post["password"], $post["confirm_password"]);
    if ($result === true) {
        return recording($pdo, trim($post["username"]), $post["password"]);
    }
    return $result;
}


// Verification validité données et stockage dans session
function log_in($pdo, $username, $password) {
    $user = user_by_username($pdo, $username);
    if ($user === false) {
        return "Identifiant ou mot de passe incorrect";
    }
    if (!password_verify($password, $user["password"])) {
        return "Identifiant ou mot de passe incorrect";
    }
    $_SESSION["id"] = $user["id"];
    return true;
}



//  Processus d'appel pour vérification puis connexion
function username_process($pdo, $post) {
    if (empty_fields($post, ["username", "password"])) {
        return "Veuillez renseigner votre login et votre mot de passe.";
    }
    return log_in($pdo, trim($post["username"]), $post["password"]);
}


// Mise à jour des informations de l'utilisateur dans BDD
function update_profile($pdo, $id, $username, $password) {
    if (username_exists($pdo, $username, $id)) {
        return "Ce nom d'utilisateur est déjà utilisé";
    }
    $sql = "UPDATE user SET username = :username, password = :password WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->execute([':username' => $username, ':password' => password_hash($password, PASSWORD_DEFAULT), ':id' => $id]);
    return true;
}


//  Processus d'appel pour vérification puis modification
function profile_modification_process($pdo, $post) {
    if (empty_fields($post, ["username", "password", "confirm_password"])) {
        return "Veuillez remplir l'ensemble des champs.";
    }
    $result = field_verification(trim($post["username"]), $post["password"], $post["confirm_password"]);
    if ($result === true) {
        return update_profile($pdo, $_SESSION["id"], trim($post["username"]), $post["password"]);
    }
    return $result;
}



// Récupération informations utilisateur par ID
function get_information_user($pdo, $id){
    $sql = "SELECT * FROM user WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->execute([':id' => $id]);
    return $query->fetch(PDO::FETCH_ASSOC);
}



// Récupération des jours de la semaine
function get_days(){
    date_default_timezone_set("Europe/Paris");
    $semaine = [];
    $debut = strtotime("monday this week");
    for ($i = 0; $i < 7; $i++) {
        array_push($semaine, date("Y-m-d", $debut));
        $debut = strtotime("+1 day", $debut);
    }
    return $semaine;
}

// Récupération des heures
function get_hours(){
    date_default_timezone_set("Europe/Paris");
    $heures = [];
    $debut = strtotime("today 08:00");
    $fin = strtotime("today 19:00");
    while ($debut < $fin) {
        array_push($heures, date("H:i", $debut));
        $debut = strtotime("+1 hour", $debut);
    }
    return $heures;
}


// Fonction récupérer tous les services proposés
function get_all_services($pdo){
    $sql = "SELECT * FROM services";
    $query = $pdo->prepare($sql);
    $query->execute([]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour savoir si le créneau est pris
function event_taken($events, $start_ts, $end_ts){
    foreach ($events as $event) {
        if ($event['start_ts'] < $end_ts && $event['end_ts'] > $start_ts) {
            return true;
        }
    }
    return false;
}

// Fonction pour enregistrer la réservation dans la BDD
function add_event($pdo, $event_title, $description, $start_date, $end_date, $creator_id){
    $sql = "INSERT INTO event (event_title, description, start_date, end_date, creator_id) VALUES (:event_title, :description, :start_date, :end_date, :creator_id)";
    $query = $pdo->prepare($sql);
    $query->execute([':event_title' => $event_title, ':description' => $description, ':start_date' => $start_date, ':end_date' => $end_date, ':creator_id' => $creator_id]);
    return true;
}


// Fonction supprimer réservation
function event_deletion($pdo, $id, $id_user){
    $sql = "DELETE FROM event WHERE id = :id AND id_user = :id_user";
    $query = $pdo->prepare($sql);
    $query->execute([':id' => $id, ':id_user' => $id_user]);
    return true;
}

// Fonction récupérer tous les RDV de la semaine
function get_all($pdo, $startWeek, $endWeek){
    $sql = "SELECT * FROM event INNER JOIN user ON user.id = event.creator_id WHERE start_date < :end AND end_date > :start";
    $query = $pdo->prepare($sql);
    $query->execute([':start' => $startWeek, ':end' => $endWeek]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction réupérer tous les RDV d'une personne
function event_by_user($pdo, $creator_id){
    $sql = "SELECT * FROM event INNER JOIN user ON user.id = event.creator_id WHERE event.creator_id = :creator_id";
    $query = $pdo->prepare($sql);
    $query->execute([':creator_id' => $creator_id]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


// Fonction récupérer RDV en particulier
function event_by_id($pdo, $id){
    $sql = "SELECT * FROM event INNER JOIN user ON user.id = event.creator_id WHERE event.id = :id";
    $query = $pdo->prepare($sql);
    $query->execute([':id' => $id]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


