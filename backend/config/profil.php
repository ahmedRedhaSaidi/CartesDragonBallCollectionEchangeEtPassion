<?php
require_once("connexion.php");

if (empty($_SESSION['user_id'])) {
    header("Location: inscription.php");
    exit();
}

// Récupère l'email de l'utilisateur
$stmt = $pdo->prepare("SELECT email FROM utilisateurs WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

// Récupère les cartes de l'utilisateur
$user_cards = getCardsForUser($pdo, $_SESSION['user_id']);



// functions affichage

function getCharacter($id) {
    $api_url = "https://dragonball-api.com/api/characters/$id";
    $response = file_get_contents($api_url);
    return json_decode($response, true); // Convertit la réponse JSON en tableau
}

function afficherCartes($liste_cartes, $type_affiche = 'normal') {
    foreach ($liste_cartes as $character_id) {
        $character = getCharacter($character_id);
        if ($character): ?>
            <div class="character-carte" 
                 data-maison="<?= strtolower($character['race'] ?? '') ?>" 
                 data-favori="<?= $type_affiche === 'favoris' ? '1' : '0' ?>">
                <button class="favBtn"><img src="../../frontend/Illustrations/logo/coeur.svg" class="btnFav"></button>
                <div class="image-container">
                    <img src="<?= $character['image'] ?? '../Illustrations/logo/back.jpg' ?>" alt="<?= $character['name'] ?? '' ?>">
                    <div class="character-info">
                        <h3><?= $character['name'] ?? 'Inconnu' ?></h3>
                        <p>Ki : <?= $character['ki'] ?? 'N/A' ?></p>
                        <p>Max Ki : <?= $character['maxKi'] ?? 'N/A' ?></p>
                        <p>Race : <?= $character['race'] ?? 'Inconnue' ?></p>
                        <p>Genre : <?= $character['gender'] ?? 'Inconnu' ?></p>
                        <p>Affiliation : <?= $character['affiliation'] ?? 'Aucune' ?></p>
                    </div>
                </div>
            </div>
        <?php endif;
    }
}

function getCardsForUser($pdo, $user_id, $favorites_only = false) {
    $sql = "SELECT character_id FROM user_cards WHERE user_id = :user_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}


if (isset($_POST['draw_cards'])) {
    $drawn_cards = []; // Stocker les cartes tirées
    for ($i = 0; $i < 5; $i++) {
        $character_id = rand(1, 30);
        $drawn_cards[] = $character_id; // Ajouter à la liste
        $stmt = $pdo->prepare("INSERT INTO user_cards (user_id, character_id) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $character_id]);
    }
    // Ajouter les cartes tirées à la session pour les afficher
    $_SESSION['drawn_cards'] = $drawn_cards;
}




























?>
































<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f0f2f5;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #e0e0e0;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .profile-pic svg {
            width: 80px;
            height: 80px;
            color: #9e9e9e;
        }

        .profile-info {
            margin-bottom: 30px;
        }

        .profile-info h1 {
            margin: 0 0 10px;
            color: #1e88e5;
        }

        .profile-info p {
            margin: 5px 0;
            font-size: 1.1em;
        }

        .logout-btn {
            background: #1e88e5;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background: #0d47a1;
        }

        a {
            text-decoration: none;
            color: #1e88e5;
            margin: 10px;
            display: inline-block;
            transition: color 0.3s, transform 0.2s;
        }

        a:hover {
            color: #0d47a1;
            transform: scale(1.05);
        }

        
        
    </style>
    <link rel="stylesheet" href="../../frontend/CSS/headerFooter.css">
    <link rel="stylesheet" href="../../frontend/CSS/tab.css">
    <link rel="stylesheet" href="../../frontend/CSS/carte.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Asap:ital,wght@0,900;1,900&family=Bungee&family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&display=swap" rel="stylesheet">
    
</head>
<body>
<div id="naveSide">
    <div id="sideNav" class="side-nav d-flex justify-content-between">
        <nav class="d-flex navItems">
            <ul class="" id="nave">
                <li class="li nav-item"><a class="a  s" href="../../HTML/shop.html">Shop</a></li>
                <li class="li nav-item"><a class="a  s" href="../../HTML/collection.html">Collection</a></li>
                <li class="li nav-item"><a class="a  s" href="../../HTML/contact.html">Booster</a></li>
                <li class="li nav-item" id="profil"><a class="a" href="../../backend/config/inscription.php">Mon profile</a></li>
            </ul>
        </nav>
        <button id="toggleBtn" class="toggle-btn">☰</button>
    </div>
</div>

<header class="d-flex justify-content-between" id="tete">
    <img src="../../frontend/Illustrations/logo/logosite.svg" alt="Logo of website King Kai" id="logo">
    <nav class="d-flex">
        <ul class="d-flex" id="nave">
            <li class="li"><a class="a  s" href="../../frontend/HTML/shop.html">Shop</a></li>
            <li class="li"><a class="a  s" href="../../frontend/HTML/collection.html">Collection</a></li>
            <li class="li"><a class="a  s" href="../../frontend/HTML/contact.html">Contacts</a></li>
            <li class="li" id="profil"><a class="a" href="/HTML/connexion.html">My profile</a></li>
        </ul>
    </nav>
</header>
    
    <div class="container">
        <div class="profile-pic">
            <!-- Icône de profil par défaut (SVG) -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>

        <div class="profile-info">
            <h1>Mon Profil</h1>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email'] ?? 'Non défini') ?></p>
        </div>

        <form action="deconnexion.php" method="post">
            <button type="submit" class="logout-btn">Se déconnecter</button>
        </form>
        <form action="" method="post" >
            <button type="submit" name="draw_cards" class="tirage">tirez 5 carte</button>
        </form>
    </div>

    
    <?php if (!empty($_SESSION['drawn_cards'])): ?>
        <div class="new-cards-section">
            <h2>Vos nouvelles cartes :</h2>
            <div class="cards-container">
                <div id="personnage-collection" class="collection">
                    <?php afficherCartes($_SESSION['drawn_cards']); ?>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['drawn_cards']); // Effacer après affichage ?>
    <?php endif; ?>

    <div class="mainCollection">
    <section>
        <div class="btnSMaison">
            <button class="btnMaison actif" data-maison="all">Toutes</button>
            <button class="btnMaison" data-maison="saiyan">Saiyan</button>
            <button class="btnMaison" data-maison="android">Android</button>
            <button class="btnMaison" data-maison="god">Dieux</button>
            <button class="btnMaison" data-maison="angel">Ange</button>
            <button class="btnMaison" data-maison="favorites">Favorites</button>
        </div>
    </section>

    
    <?php
// 1. Récupérer les cartes de l'utilisateur depuis la BDD
$stmt = $pdo->prepare("SELECT DISTINCT character_id FROM user_cards WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user_cards = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 2. Afficher les cartes avec votre fonction
if (!empty($user_cards)) {
    echo '<h2>Ma Collection de Cartes</h2>';
    echo '<div class="cartes-container">';
    echo '<div id="personnage-collection" class="collection">';
    
    afficherCartes($user_cards);
    
    echo '</div></div>';
} else {
    echo '<p class="aucune-carte">Vous n\'avez aucune carte dans votre collection.</p>';
}
?>
        
    </div>
</div>
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
                <li class="li"><a class="  s  footerLink" href="../../frontend/HTML/shop.html">Shop</a></li>
                <li class="li"><a class="  s  footerLink" href="../../frontend/HTML/collection.html">Collection</a></li>
                <li class="li"><a class="  s  footerLink" href="../../frontend/HTML/contact.html">Contacts</a></li>
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
<script src="../../frontend/JS/main.js">

</script>

</body>
</html>
