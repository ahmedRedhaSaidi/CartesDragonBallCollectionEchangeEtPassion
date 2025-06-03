<?php
require_once("connexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $motDePasse = trim($_POST["mot_de_passe"]);

    if ($email && $motDePasse) {
        $hash = password_hash($motDePasse, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe) VALUES (:email, :mot_de_passe)");

        try {
            $stmt->execute([
                "email" => $email,
                "mot_de_passe" => $hash
            ]);
            
            // recupérer l'ID du dernier utilisateur inséré
            $userId = $pdo->lastInsertId();
            
            // creer la session avec seulement les données disponibles
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_email'] = $email;
            
            header("Location: profil.php");
            exit();

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
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
                <li class="li"><a class="a  s" href="../../frontend/HTML/HTML/shop.html">Shop</a></li> 
                <li class="li"><a class="a  s" href="../../frontend/HTML/collection.html">Collection</a></li>
                <li class="li"><a class="a  s" href="../HTML/contact.html"> Booster</a></li>
                <li class="li" id="profil"><a class="a" href="../HTML/connexion.html" >Mon profile</a></li>
            </ul>
        </nav>
    </header>

<main>

<div id="forme" class="container d-flex justify-content-center">
    <div class="form-box containerCo" >
        <a href="login.php" class="btnCo" >se connecter</a>
        <button type="button" class="coin">inscritption</button>
        <form class=" ino" action="#" method="post">
            <label for="Nom">Nom :</label>
            <input id="Nom" type="text" placeholder="Nom complet"class="in" required>
            <label for="email">E-mail :</label>
            <input id="email" placeholder="Email" class="in" name='email' required>
            <label for="mdp1">Mot de passe :</label>
            <input id="mdp1" type="password" placeholder="Mot de passe" class="in" name='mot_de_passe' required>
            <label for="mdp2">Confirmez votre mot de passe :</label>
            <input id="mdp2" type="password" placeholder="Confirmer le mot de passe" class="in" required>
            <div id="checkbox-container">
                <div class="checkbox-item">
                    <input type="checkbox" id="age-check">
                    <label for="age-check">J'ai plus de 18 ans</label>
                </div>
                <div class="checkbox-item">
                    <input type="checkbox" id="accept-check">
                    <label for="accept-check">J'accepte les conditions générales.</label>
                </div>
            </div>
            <button type="submit" class="btnCo" >S'inscrire</button>
  
        </form>
    </div>
</div>

</main>

<footer id="pied" class="d-flex justify-content-between"><!--debut du footer-->
    <div><!--liste des moyen de nous contacter-->
        <ul>
            <li class="li footerTitle"><h2>CONTACTEZ-NOUS</h2></li>
            <li class="li"><a class=" footerLink" href="">mail</a></li>
            <li class="li"><a class=" footerLink" href="">telephone</a></li>
            <li class="li"><a class=" footerLink" href="">whatapp</a></li>
        </ul>
    </div>
    <div>
        <ul><!--retour au menu-->
            <li class="li footerTitle"><h2>MENU</h2></li>
            <li class="li"><a class="  s  footerLink" href="/HTML/shop.html">Shop</a></li>
            <li class="li"><a class="  s  footerLink" href="/HTML/collection.html">Collection</a></li>
            <li class="li"><a class="  s  footerLink" href="/HTML/contact.html">Contacts</a></li>
        </ul>
    </div>
    <div>
        <div class="justify-content-between"><!--lien pour suivre ou contacter-->
            <a class="a" href=""><img id="imgContact"  src="../../frontend/Illustrations/logo/facebook.svg" alt="Facebook contact" srcset=""></a>
            <a class="a" href=""><img id="imgContact" src="../../frontend/Illustrations/logo/X (formerly Twitter)_dark.svg" alt="X contact" srcset=""></a>
            <a class="a" href=""><img id="imgContact" src="../../frontend/Illustrations/logo/linkedin.svg" alt="Linkdin Contact" srcset=""></a>
            <a class="a" href=""><img id="imgContact" src="../../frontend/Illustrations/logo/telegram.svg" alt="Telegram contact" srcset=""></a>
        </div>
        <div>
            <p>Dragon ball hero – &copy; 2025 Tous droits réservés <br><!--mention legal et rgpd-->
                <a id="legal" href="">Mentions Légales | CGU |<br> Politique de Confidentialité | Politique de Cookies</a>
                </p>
        </div>
    </div>

</footer>
<script src="../JS/main.js"></script>
<script>
     const email = document.getElementById("email");
    email.value = localStorage.getItem("email") || "";
    email.oninput = () => localStorage.setItem("email", email.value);
</script>

</body>

</html>
