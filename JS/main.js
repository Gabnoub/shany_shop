// =================================== Automatic Caroussel with Loadbars =============================================//
const images = document.querySelector(".caroussel__container");
const progressBars = document.querySelectorAll(".progress-bar");
const progressBars__cat = document.querySelectorAll(".progress-bar__cat");
const Cats = document.querySelector(".categories__item");

const slides = document.querySelectorAll(".caroussel__image");

let index = 0;
const totalImages = document.querySelectorAll(".caroussel__container img").length;

function changeImage() {
    index = (index + 1) % totalImages;
    // images.style.transform = `translateX(-${index * 100}%)`;

    progressBars.forEach(bar => bar.classList.remove("active"));
    progressBars[index].classList.add("active");


    slides.forEach(slide => slide.classList.remove("active"));
    slides[index].classList.add("active");
}

const myinterval = setInterval(changeImage, 3000);

// Activer la première barre au démarrage
progressBars[index].classList.add("active");


// =======================================  Activate and desactivate category  ==========================================//
// Sélection de la barre de progression
const pb = document.getElementById("pbc");
const productContainer = document.getElementById("productContainer");
let currentCategory = ""; // Standard: Colliers

currentCat(0);
function currentCat(index) {
    // Déplacer la barre progressive sous le bouton sélectionné
    const test = document.querySelector('.categories__item:nth-child(1)');
    const breite = test.offsetWidth;
    
    const index_cat = (index-2) % 4;
    // pb.style.transform = `translateX(${index * 100}%)`;
    pb.style.transform = `translateX(${index_cat * breite}px)`;

    // Mettre à jour le contenu des produits
    

    const itemsHTML = products[index].map(item => {
        const hasDiscount = item.price !== item.finalprice;
        currentCategory = item.catSlug;
        const discount = hasDiscount 
        ? Math.round(100 - (item.finalprice * 100) / item.price)
        : 0;
      
        return `
          <div class="product-item">
            
            <a class="pr_link" href="products/${item.slug}">
              <img src="${item.image}" class="pr_image">
            
            <p class="pr__title">${item.title}</p>
            <p class="pr__price">
              ${hasDiscount
                ? `<del style="text-decoration: line-through;">${item.price}</del> 
             <strong>${item.finalprice} CFA</strong>
             <button class="rabatt">-${discount} %</button>` 
          : `<strong>${item.finalprice} CFA</strong>`
              }
            </p>
            </a>
          </div>
        `;
      }).join("");

  productContainer.innerHTML = itemsHTML;
    
}


// Wenn Explorer geklickt wird, zur Kategorie-Seite springen
document.getElementById("exploreBtn").addEventListener("click", function () {
    // Weiterleitung zur passenden Seite (z. B. category.php?id=...)
    window.location.href = `categories/${currentCategory}`;
  });