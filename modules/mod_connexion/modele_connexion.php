<?php

class ModeleConnexion extends Connexion {

    public function get_utilisateur($login) {
        $req = self::$bdd->prepare("SELECT * FROM Utilisateur WHERE login = ?");
        $req->execute([$login]);
        return $req->fetch();
    }

    public function hash_passwords() {
        $users = self::$bdd->query("SELECT id_utilisateur, mdp FROM Utilisateur")->fetchAll();
        foreach ($users as $user) {
            $hashed = password_hash($user['mdp'], PASSWORD_DEFAULT);
            $stmt = self::$bdd->prepare("UPDATE Utilisateur SET mdp = ? WHERE id_utilisateur = ?");
            $stmt->execute([$hashed, $user['id_utilisateur']]);
        }
    }
}
