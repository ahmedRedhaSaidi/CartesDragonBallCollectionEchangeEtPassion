document.addEventListener('DOMContentLoaded', () => {
    async function getCharacter(id) {
        const response = await fetch(`https://dragonball-api.com/api/characters/${id}`);
        return response.json();
    }

    async function showDetails() {
        const id = new URLSearchParams(window.location.search).get("id");
        const container = document.getElementById("carte-detail");
        
        const character = await getCharacter(id);
        
        container.innerHTML = `
<div class="carte-personnage">
    <button class="bouton-favori">
        <img src="../Illustrations/logo/coeur.svg" class="icone-favori" alt="Favoris">
    </button>
    
    <div class="contenu-principal">
        <div class="visuel-personnage">
            <img src="${character.image}" alt="${character.name}" class="portrait-personnage">
            <img src="../Illustrations/logo/back.jpg" class="fond-personnage">
        </div>
        
        <div class="details-personnage">
            <h2 class="titre-personnage">${character.name}</h2>
            
            <div class="grille-stats">
                <div class="item-stat">
                    <span class="libelle-stat">Ki:</span>
                    <span class="valeur-stat valeur-ki">${character.ki}</span>
                </div>
                
                <div class="item-stat">
                    <span class="libelle-stat">Ki Max:</span>
                    <span class="valeur-stat valeur-kimax">${character.maxKi}</span>
                </div>
                
                <div class="item-stat">
                    <span class="libelle-stat">Race:</span>
                    <span class="valeur-stat valeur-race">${character.race}</span>
                </div>
                
                <div class="item-stat">
                    <span class="libelle-stat">Genre:</span>
                    <span class="valeur-stat valeur-genre">${character.gender}</span>
                </div>
                
                <div class="item-stat pleine-largeur">
                    <span class="libelle-stat">Affiliation:</span>
                    <span class="valeur-stat valeur-affiliation">${character.affiliation}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="biographie">
        <h3 class="titre-bio">Description</h3>
        <p class="texte-bio">${character.description}</p>
    </div>
</div>`;
    }

    showDetails();
});