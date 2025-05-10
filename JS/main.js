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

const myinterval = setInterval(changeImage, 6000);

// Activer la première barre au démarrage
progressBars[index].classList.add("active");


function currentSlide(i) {

  // Stop automatic mode
  clearInterval(myinterval);

  progressBars.forEach(bar => bar.classList.remove("active"));
  progressBars.forEach(bar => bar.classList.remove("active__after_clicked"));

  slides.forEach(slide => slide.style.display = "none");
  index = i;
  progressBars[index].classList.add("active__after_clicked");
  slides[index].style.display = "block";

}

// =======================================  Activate and desactivate category  ==========================================//
// Sélection de la barre de progression
const pb = document.getElementById("pbc");
const pb_breite = pb.offsetWidth;
const productContainer = document.getElementById("productContainer");
const cat_el = document.querySelector('.categories__item:nth-child(1)');
const breite = cat_el.offsetWidth;

const _catslug = document.getElementById("catslug").dataset.catSlug;
const _catslug1 = document.getElementById("catslug1").dataset.catSlug;
const _catslug2 = document.getElementById("catslug2").dataset.catSlug;
const _catslug3 = document.getElementById("catslug3").dataset.catSlug;
const _cat_slug = [_catslug, _catslug1, _catslug2, _catslug3];
let currentCategory = ""; // Standard: Colliers

currentCat(0);
function currentCat(index) {
    // Déplacer la barre progressive sous le bouton sélectionné
    
    
    const index_cat = (index-2) % 4;
    // pb.style.transform = `translateX(${index * 100}%)`;
    pb.style.transform = `translateX(${index_cat * breite}px)`;

    // Mettre à jour le contenu des produits
    currentCategory = _cat_slug[index];

    const itemsHTML = products[index].map(item => {
        const hasDiscount = item.price !== item.finalprice;
        // currentCategory = _cat_slug[index];
        const discount = hasDiscount 
        ? Math.round(100 - (item.finalprice * 100) / item.price)
        : 0;
      
        return `
          <div class="product-item">
            
            <a class="pr_link" href="products/${item.slug}">
              <img src="${item.image}" class="pr_image">
            
            <p class="pr__title"><strong>${item.title}</strong></p>
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
// =======================================  Activate and desactivate category  ==========================================//
document.addEventListener("DOMContentLoaded", () => {
  
  // category 
  pb.style.left = `-${(breite + breite/2 + pb_breite/2)}px`;

})