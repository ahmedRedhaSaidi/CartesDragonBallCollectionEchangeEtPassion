<?php
// importe le fichier avec la connexion à la base de donné + démarre la session
require_once("connexion.php");

// verifie si l'utilisateur est deja connecté
// si une session existe avec son id il est redirigé vers la page profil
if (isset($_SESSION["user_id"])) {
    header("Location: profil.php"); // redirige vers la page profil
    exit; // stop le script apres la redirection
}

// message qui servira a afficher une erreur ou une info
$message = "";

// verifie si le formulaire a bien été envoyer (en POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // recupere les champs email et mot de passe et enleve les espaces autour
    $email = trim($_POST["email"]);
    $password = trim($_POST["mot_de_passe"]);

    // si les deux champs sont pas vide
    if ($email && $password) {
        // prepare la requete pour chercher un utilisateur avec cet email
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        
        // execute la requete avec le mail fournie
        $stmt->execute(["email" => $email]);
        
        // recupere le resultat sous forme de tableau associatif
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // si un utilisateur est trouvé et que le mot de passe correspond
        if ($user && password_verify($password, $user["mot_de_passe"])) {
            // créer la session avec les infos de l'utilisateur
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["email"] = $user["email"];
            
            // redirige vers la page profil
            header("Location: profil.php");
            exit;
        } else {
            // si le mot de passe ou le mail est mauvais affiche un message d'erreur
            $message = "Email ou mot de passe incorrect.";
        }
    } else {
        // si un des deux champ est vide affiche un message d'avertissement
        $message = "Veuillez remplir tous les champs.";
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection</title>
    <!--icon--> 
    <link rel="shortcut icon" href="../Illustrations/logo/logosite.svg" type="image/x-icon">
    <!--link css--> 
    <link rel="stylesheet" href="../../frontend/CSS/style.css">
    <!--link police--> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asap:ital,wght@0,100..900;1,100..900&family=Bungee&family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&display=swap" rel="stylesheet">
</head>

<body>
    
    <header class="d-flex justify-content-between" id="tete"><!--un header avec ma nav bar et mon logo disposer a chaque coin--> 
        <img src="../Illustrations/logo/logosite.svg" alt="Logo of website King Kai" srcset="" id="logo">
        <nav class="d-flex"><!--ma nav avec les page principal--> 
            <ul class="d-flex" id="nave">
                <li class="li"><a class="a  s" href="../../frontend/HTML/shop.html">Shop</a></li> 
                <li class="li"><a class="a  s" href="../../frontend/HTML/collection.html">Collection</a></li>
                <li class="li"><a class="a  s" href="../../frontend/HTML/contact.html">Booster</a></li>
                <li class="li" id="profil"><a class="a" href="../../frontend/HTML/login.php" >Mon profile</a></li>
            </ul>
        </nav>
    </header>

<main>
            <div id="forme" class="container d-flex justify-content-center">
                <div class="form-box containerCo" >
                    <a href="inscription.php" class="btnCo" >s'inscrire</a>
                    <button type="button" class="coin">Connexion</button>
                    <form class="ino" action="login.php" method="post">
                        <label for="email">E-mail :</label>
                        <input id="email" placeholder="Email" class="in" name='email' required>
                        <label for="mdp1">Mot de passe :</label>
                        <input id="mdp1" type="password" placeholder="Mot de passe" class="in" name='mot_de_passe' required>
                        <button type="submit" class="btnCo" >se connecter</button>
                        </form>
                </div>
            </div>

</main>
    <script>
         const email = document.getElementById("email");
        email.value = localStorage.getItem("email") || "";
        email.oninput = () => localStorage.setItem("email", email.value);
    </script>
</body>
</html>
