<?php

/**
 *  Lecture d'un fichier JSON sécurisé
 */
function lire_json($chemin_fichier) {

    if (!file_exists($chemin_fichier)) {
        die(" Fichier introuvable : " . $chemin_fichier);
    }

    $contenu = file_get_contents($chemin_fichier);
    $data = json_decode($contenu, true);

    if ($data === null) {
        die(" JSON invalide : " . $chemin_fichier);
    }

    return $data;
}

/* ===============================
    Charger les données à partir des fichiers JSON
   =============================== */

function charger_salles($chemin) {
    return lire_json($chemin);
}

function charger_promotions($chemin) {
    return lire_json($chemin);
}

function charger_cours($chemin) {
    return lire_json($chemin);
}

function charger_options($chemin) {
    return lire_json($chemin);
}

/* ===============================
    CONTRAINTES DU SYSTÈME
   =============================== */

/**
 * Vérifie si une salle est libre à un créneau donné
 */
function salle_disponible($planning, $id_salle, $creneau) {

    foreach ($planning as $p) {

        if (
            $p['salle_id'] == $id_salle &&
            $p['jour'] == $creneau['jour'] &&
            $p['heure_debut'] == $creneau['heure_debut']
        ) {
            return false;
        }
    }

    return true;
}

/**
 * Vérifie si la capacité de la salle est suffisante
 */
function capacite_suffisante($salles, $id_salle, $effectif) {

    foreach ($salles as $salle) {

        if ($salle['id'] == $id_salle) {
            return $effectif <= $salle['capacite'];
        }
    }

    return false;
}

/**
 * Vérifie si un groupe est libre à un créneau donné
 */
function creneau_libre_groupe($planning, $id_groupe, $creneau) {

    foreach ($planning as $p) {

        if (
            $p['groupe'] == $id_groupe &&
            $p['jour'] == $creneau['jour'] &&
            $p['heure_debut'] == $creneau['heure_debut']
        ) {
            return false;
        }
    }

    return true;
}

/* ===============================
    VALIDATION COMBINÉE (BONNE PRATIQUE)
   =============================== */

/**
 * Vérifie toutes les contraintes en une seule fonction
 */
function est_affectation_valide($planning, $salles, $id_salle, $id_groupe, $effectif, $creneau) {

    return (
        salle_disponible($planning, $id_salle, $creneau) &&
        capacite_suffisante($salles, $id_salle, $effectif) &&
        creneau_libre_groupe($planning, $id_groupe, $creneau)
    );
}
// ===============================
//  GÉNÉRATEUR DE PLANNING
// ===============================
function generer_planning($salles, $promotions, $cours, $options, $creneaux) {

    $planning = [];

    foreach ($cours as $c) {

        // =========================
        //  Déterminer l'effectif du groupe
        // =========================
        $effectif = 0;

        if ($c['type'] == "tronc") {

            foreach ($promotions as $p) {
                if ($p['id'] == $c['groupe']) {
                    $effectif = $p['effectif'];
                }
            }

        } else {

            foreach ($options as $o) {
                if ($o['id'] == $c['groupe']) {
                    $effectif = $o['effectif'];
                }
            }
        }

        // =========================
        //  Rechercher une salle et un créneau disponibles
        // =========================
        $affecte = false;

        foreach ($creneaux as $creneau) {

            foreach ($salles as $salle) {

                if (est_affectation_valide(
                    $planning,
                    $salles,
                    $salle['id'],
                    $c['groupe'],
                    $effectif,
                    $creneau
                )) {

                    $planning[] = [
                        "cours_id" => $c['id'],
                        "salle_id" => $salle['id'],
                        "jour" => $creneau['jour'],
                        "heure_debut" => $creneau['heure_debut'],
                        "heure_fin" => $creneau['heure_fin'],
                        "groupe" => $c['groupe']
                    ];

                    $affecte = true;
                    break 2; // sortir des deux boucles
                }
            }
        }

        // =========================
        // Afficher un message d'erreur si le cours n'a pas pu être affecté
        // =========================
        if (!$affecte) {
            echo "Impossible d'affecter le cours : " . $c['id'] . "<br>";
        }
    }

    return $planning;
}
// ===============================
//  FONCTION DE SAUVEGARDE DU PLANNING
// ===============================
function sauvegarder_planning($planning, $chemin_txt, $chemin_json) {

    // =========================
    //  Sauvegarder dans un fichier TXT
    // =========================
    $contenu = "";

    foreach ($planning as $p) {

        $ligne = $p['jour'] . " | " .
                 $p['heure_debut'] . "-" . $p['heure_fin'] . " | " .
                 $p['salle_id'] . " | " .
                 $p['cours_id'] . " | " .
                 $p['groupe'] . "\n";

        $contenu .= $ligne;
    }

    file_put_contents($chemin_txt, $contenu);

    // =========================
    // Sauvegarder dans un fichier JSON
    // =========================
    file_put_contents(
        $chemin_json,
        json_encode($planning, JSON_PRETTY_PRINT)
    );

    echo " Planning sauvegardé avec succès !<br>";
}
// ===============================
//  FONCTION DE DÉTECTION DE CONFLITS
// ===============================  
function detecter_conflits($planning) {

    $conflits = [];

    for ($i = 0; $i < count($planning); $i++) {

        for ($j = $i + 1; $j < count($planning); $j++) {

            $p1 = $planning[$i];
            $p2 = $planning[$j];

            // =========================
            //  même jour + même heure
            // =========================
            $meme_creneau =
                $p1['jour'] == $p2['jour'] &&
                $p1['heure_debut'] == $p2['heure_debut'];

            if ($meme_creneau) {

                // =========================
                // CONFLIT SALLE
                // =========================
                if ($p1['salle_id'] == $p2['salle_id']) {
                    $conflits[] = "Conflit salle : "
                        . $p1['salle_id']
                        . " le " . $p1['jour']
                        . " à " . $p1['heure_debut'];
                }

                // =========================
                //  CONFLIT GROUPE
                // =========================
                if ($p1['groupe'] == $p2['groupe']) {
                    $conflits[] = " Conflit groupe : "
                        . $p1['groupe']
                        . " le " . $p1['jour']
                        . " à " . $p1['heure_debut'];
                }
            }
        }
    }

    return $conflits;
}
// ===============================
//  FONCTION DE GÉNÉRATION DE RAPPORT D'OCCUPATION
// ===============================
function generer_rapport_occupation($planning, $salles) {

    $total_creneaux = 10; 

    $rapport = "";

    foreach ($salles as $salle) {

        $id = $salle['id'];
        $nom = $salle['designation'];

        $occ = 0;

        // =========================
        //  Compter les occupations
        // =========================
        foreach ($planning as $p) {
            if ($p['salle_id'] == $id) {
                $occ++;
            }
        }

        $libre = $total_creneaux - $occ;

        // =========================
        //  Éviter division par zéro
        // =========================
        $taux = ($total_creneaux > 0)
            ? ($occ / $total_creneaux) * 100
            : 0;

        // =========================
        // Construction du texte
        // =========================
        $rapport .= "Salle : $nom ($id)\n";
        $rapport .= "Occupés : $occ\n";
        $rapport .= "Libres : $libre\n";
        $rapport .= "Taux : " . round($taux, 2) . "%\n";
        $rapport .= "-----------------------------\n";
    }

    // =========================
    //  Sauvegarde fichier
    // =========================
    file_put_contents("data/rapport_occupation.txt", $rapport);

    return $rapport;
}
?>