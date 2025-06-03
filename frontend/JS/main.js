
// sidenav

var sideNav = document.getElementById("sideNav");
var toggleBtn = document.getElementById("toggleBtn");

toggleBtn.addEventListener("click", function() {
    sideNav.classList.toggle("open");
});









       // 1. Recupération des personnages par l'api et la methode fetch de maniere asyncrone
       async function fetchPersonnage() {
        const response = await fetch("https://dragonball-api.com/api/characters?limit=60");
        const data = await response.json();
        return data.items;
        }
    
        // 2. Affichage des personnages
        async function deploimentPersonnage() {
            const allCharacters = await fetchPersonnage();
            const container = document.getElementById("personnage-collection");
            const dataList = document.getElementById("carteChar");
    
            allCharacters.forEach(function(character) {
                const maison = character.race; // catégorie pour le filtre 


                let option = document.createElement("option");// data liste
                option.value = character.name;
                dataList.appendChild(option);
        
    
                const div = document.createElement("div");
                div.className = "character-carte";
                div.setAttribute("data-maison", maison); // <-ajout de la maison pour la mise en place du filtre
                                // Creation de div du personnage 
                div.innerHTML = `
                    <span onclick="navigateToCharacter(${character.id})" class="character-link" id ="carte-detail">
                    <button class="favBtn"><img src="../Illustrations/logo/coeur.svg" class="btnFav"></button>        
                    <div class="image-container">
                        <img src="${character.image || 'placeholder.jpg'}" alt="${character.name}" />
                        <img src="../Illustrations/logo/back.jpg" class="back"/>
                        <div class="character-info">
                            <h3>${character.name}</h3>
                            <p>Ki : ${character.ki || "Non disponible"}</p>
                            <p>Max Ki : ${character.maxKi || "Non disponible"}</p>
                            <p>Race : ${character.race || "Non disponible"}</p>
                            <p>Genre : ${character.gender || "Non disponible"}</p>
                            <p>Affiliation : ${character.affiliation || "Non disponible"}</p>
                        </div>
                    </div>
                    </span>
                    
                `;
    
                container.appendChild(div);
                
                     // Ajout à la datalist
                option.value = character.name;
                dataList.appendChild(option);
            });



                    //bouton favorie
                    var favBtns = document.querySelectorAll(".favBtn");
                    favBtns.forEach(function(btn) {
                        btn.addEventListener("click", function() {
                            var icon = btn.querySelector(".btnFav");
                            var carte = btn.closest(".character-carte");
                            var container = document.getElementById("personnage-collection");
                            
                            // Basculer les classes
                            icon.classList.toggle("actif");
                            carte.classList.toggle("active");
                            
                            // Si la carte devient favorite
                            if (carte.classList.contains("active")) {
                                // Retirer la carte de sa position
                                container.removeChild(carte);
                                // La remettre au début
                                container.insertBefore(carte, container.firstChild);
                                
                            }
                        });
                    });
            
        }
    
    
        deploimentPersonnage();

    
        // Fonction de navigation
            window.navigateToCharacter = function(id) {
            window.location.href = `carte.html?id=${id}`;
        };


        // 3. Filtrage des personnages
        const boutonsOnglet = document.querySelectorAll(".btnMaison");
    
        boutonsOnglet.forEach(function (bouton) {
            bouton.addEventListener("click", function () {
                const maisonChoisie = bouton.getAttribute("data-maison");
    
                // Bouton actif visuel
                boutonsOnglet.forEach(btn => btn.classList.remove("actif"));
                bouton.classList.add("actif");
    
                // Filtrage
                const cartes = document.querySelectorAll(".character-carte");
                cartes.forEach(carte => {
                    const maisonPersonnage = carte.getAttribute("data-maison").toLowerCase();
    
                    if (maisonChoisie === "all" || maisonPersonnage.includes(maisonChoisie)) {
                        carte.style.display = "block";  // Afficher la carte
                    } else {
                        carte.style.display = "none";   // Masquer la carte
                    }
                });
            });
        });


        //4. Apparitions du bouton flotant 

        const btn = document.getElementById("btn");
        const message = document.getElementById("message");
    
        btn.addEventListener("click", () => {
          message.classList.toggle("visible");
        });




        //5. local storage
        
        email.value = localStorage.getItem("email") || "";
        email.oninput = () => localStorage.setItem("email", email.value);




