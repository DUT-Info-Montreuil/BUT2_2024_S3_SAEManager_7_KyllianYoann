<?php 
class ModeleConnexion extends Connexion {

    public function get_utilisateur($login) {
        // Préparer la requête pour récupérer l'utilisateur avec son rôle
        $req = self::$bdd->prepare("SELECT * FROM utilisateur WHERE login = ?");
        $req->execute([$login]);
        return $req->fetch();
    }
}


// On a enlevé "ajout_utilisateur" car on ne permet pas l'inscription c'est nous on vas créer les identifiants et les donner comme l'ent...