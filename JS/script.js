// =================================== Currentslide show and stop automatic caroussel=============================================//

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
// =======================================  Activate and desactivate drop-down menu  ==========================================//

const navLinks = document.querySelector(".nav__links");
const menuBtn = document.getElementById("menu__icon");
// const menuBtnClose = document.getElementById("menu__icon-close");
menuBtn.addEventListener("click", function() {
    navLinks.classList.toggle("active");
    menuBtn.innerHTML = navLinks.classList.contains("active") ? "✖" : "☰";
}
)

document.addEventListener("click", function (event) {
    if (!navLinks.contains(event.target) && !menuBtn.contains(event.target)) {
        navLinks.classList.remove("active");
        menuBtn.textContent = "☰";
    }
})



// =======================================  Activate and desactivate Cart section  ==========================================//
const icon = document.getElementById("cart__icon");
const cart = document.getElementById("cartCont");
const close = document.getElementById("close__icon");
const opCart = document.querySelector(".open__cart");
const cQty = document.getElementById("cart__qty");

icon.addEventListener("click", function() {
    cart.style.transform =  `translateX(${0}%)`;
    document.body.style.overflow = "hidden"; // 🔒 Scroll sperren
})

close.addEventListener("click", function() {
    cart.style.transform =  `translateX(${100}%)`;  
    document.body.style.overflow = "auto"; // ✅ Scroll erlauben
})

// =======================================  Charge selected thumbnail as first pic in product page  ==========================================//

document.querySelectorAll(".tn__image").forEach(input => {
    input.addEventListener("click", function (event) {
    const tnAll = document.querySelectorAll(".tn__image");
    tnAll.forEach(tn => tn.classList.remove("active"));
    event.target.classList.add("active");
    const tnImageSrc = event.target.src;
    document.querySelector(".main__prImage").src = tnImageSrc;
    });
  });
// =======================================  Add to cart button was clicked  ==========================================//
  window.addEventListener("pageshow", () => {
    renderCart();
  });

// Warenkorb auslesen
function getCart() {
  return JSON.parse(localStorage.getItem("cart")) || {};
}

// Warenkorb speichern
function saveCart(cart) {
  localStorage.setItem("cart", JSON.stringify(cart));
}

// Produkt hinzufügen
function addToCart(product) {
  const cart = getCart();
  if (cart[product.id]) {
    cart[product.id].qty += 1;
  } else {
    cart[product.id] = { ...product, qty: 1 };
  }
  saveCart(cart);
  renderCart();
}

// Produkt entfernen
function removeItem(id) {
  const cart = getCart();
  delete cart[id];
  saveCart(cart);
  renderCart();
}

// Menge aktualisieren
function changeQty(id, delta) {
  const cart = getCart();
  if (cart[id]) {
    cart[id].qty += delta;
    if (cart[id].qty <= 0) delete cart[id];
    saveCart(cart);
    renderCart();
  }
}

const rootUrl = document.getElementById("app").dataset.rootUrl;
// Warenkorb rendern
function renderCart() {
  const cart = getCart();
  const container = document.getElementById("cp-items");
  const qtyCounter = document.getElementById("cart__qty");
  const qtyCounter_new = document.getElementById("cart__qty_new");
  
  container.innerHTML = "";
  let totalItems = 0;
  let totalPrice = 0;
  
  let message = `🛍️ Nouvelle commande: \n`;
  // 💰 Gesamtpreis berechnen
  let total_price = Object.values(cart).reduce((sum, p) => sum + p.qty * p.price, 0);
  let summe = total_price.toLocaleString('de-DE') + " CFA";

  if (Object.keys(cart).length === 0) {
    let html = `<div style="color:black; width:200px; text-align:center; font-size:medium"><strong>Votre panier est actuellement vide.</strong></div>`;
    container.innerHTML = html;
  } else {
    for (let key in cart) {
      const p = cart[key];
      totalItems += p.qty;
      const lineTotal = p.qty * p.price;
      totalPrice += lineTotal;
      message += `• (${p.title}, ${p.color}) x${p.qty} = ${lineTotal.toLocaleString('de-DE')} CFA\n`;

      const el = document.createElement("div");
      el.className = "cart__product-item";
      el.innerHTML = `
         
            <a class="cart__pr_link" href="${rootUrl}products/${p._slug}"><img src="${p.image}"></a>
            <div class="cart__right">
                <div class="cart_description">
                    <p class="cart__pr__title"><strong>${p.title}</strong></p>
                    <p class="cart__pr__price">${p.price} CFA</p>
                    <p class="">${p.color}</p>
                </div>
                <div class="quantity">
                  <div class="qty_btns">
                    <button style="cursor:pointer;" class="qty-minus" data-id="${p.id}">-</button>
                    <button style="cursor:pointer;" class="qty-display" data-id="${p.id}">${p.qty}</button>
                    <button style="cursor:pointer;" class="qty-plus" data-id="${p.id}">+</button>
                  </div>
                  <button class="cart_delete" onclick="removeItem('${p.id}')"><i class="uil uil-trash-alt"></i></button>
                </div>
                
            </div>
          `;
      container.appendChild(el);
    }
    const el1 = document.createElement("div");
      el1.className = "cart_bottom";
      el1.innerHTML = `
      <div id="cart_total">Total de votre commande</div>
      <p id="cart__total" style="color:black; text-align:center; font-size:small"></p>
      <button id="whatsappCheckout">Commander via <i class="uil uil-whatsapp"></i> Whatsapp</button>
      `;

      container.appendChild(el1);

      const totalEl = document.getElementById("cart__total");
      if (totalEl) {
        totalEl.textContent = summe;
      } else {
        console.warn("Element #cart__total not found");
      }

    message += `\n💰 Total: ${totalPrice.toLocaleString('de-DE')} CFA`;
    

    if (totalEl) totalEl.textContent = totalPrice.toLocaleString('de-DE') + " CFA";
    
    const checkoutBtn = document.getElementById("whatsappCheckout");
    if (checkoutBtn) {
      checkoutBtn.onclick = () => {
        const phone = "+237652042276";
        const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
        window.open(url, "_blank");
        // fetch order details
        
  // Bestellung an die Datenbank senden
  const bestellDaten = {
    message: message,
  };

  fetch(`${rootUrl}admin/save_orders.php`, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(bestellDaten),
  })
  .then(response => response.text())
  .then(data => {
    console.log("Antwort vom Server:", data);
  })
  .catch(error => {
    console.error("Fehler beim Senden der Bestellung:", error);
  });


        
      
      };
    }
  }

  if (totalItems === 0) {
    qtyCounter.style.display = "none";
    qtyCounter_new.style.display = "none";
  } else {
    qtyCounter.textContent = totalItems;
    qtyCounter_new.textContent = totalItems;
    qtyCounter.style.display = "block";
    qtyCounter_new.style.display = "block";
  }
  

  // Buttons registrieren
  document.querySelectorAll(".qty-plus").forEach(btn =>
    btn.addEventListener("click", () => changeQty(btn.dataset.id, 1))
  );
  document.querySelectorAll(".qty-minus").forEach(btn =>
    btn.addEventListener("click", () => changeQty(btn.dataset.id, -1))
  );
  document.querySelectorAll(".remove").forEach(btn =>
    btn.addEventListener("click", () => removeItem(btn.dataset.id))
  );
}
//------------------------------------------------ Initialisierung -----------------------------------------------------------------------------//
document.addEventListener("DOMContentLoaded", () => {
// New products Slider
const newProductContainer = document.querySelector('.new__products-container');
const prevButton = document.querySelector('.prev-button');
const nextButton = document.querySelector('.next-button');
const dots = document.querySelectorAll('.dot');
if (newProductContainer && prevButton && nextButton) {
  
function getProductsToShow() {
  if (window.innerWidth <= 480) {
    return 1;
  } else if (window.innerWidth <= 768) {
    return 2;
  } else {
    return 4;
  }
}

function calculateScrollAmount() {
  const productWidth = newProductContainer.querySelector('.new__product-item').offsetWidth;
  const gap = 20;
  return (productWidth + gap) * getProductsToShow();
}

let currentIndex = 0;

function updateDots() {
  const productsToShow = getProductsToShow();
  const maxIndex = Math.ceil(newProductContainer.children.length / productsToShow) - 1;
  
  dots.forEach((dot, index) => {
    if (index <= maxIndex) {
      dot.style.display = 'block';
      dot.classList.toggle('active', index === currentIndex);
    } else {
      dot.style.display = 'none';
    }
  });
}

nextButton.addEventListener('click', function() {
  const productsToShow = getProductsToShow();
  if ((currentIndex + 1) * productsToShow < newProductContainer.children.length) {
    currentIndex++;
    newProductContainer.scrollLeft = calculateScrollAmount() * currentIndex;
    updateDots();
    updateButtonVisibility();
  }
});

prevButton.addEventListener('click', function() {
  if (currentIndex > 0) {
    currentIndex--;
    newProductContainer.scrollLeft = calculateScrollAmount() * currentIndex;
    updateDots();
    updateButtonVisibility();
  }
});

dots.forEach((dot, index) => {
  dot.addEventListener('click', function() {
    currentIndex = index;
    newProductContainer.scrollLeft = calculateScrollAmount() * index;
    updateDots();
    updateButtonVisibility();
  });
});

function updateButtonVisibility() {
  const productsToShow = getProductsToShow();
  prevButton.style.opacity = currentIndex === 0 ? '0.5' : '1';
  prevButton.style.pointerEvents = currentIndex === 0 ? 'none' : 'auto';
  
  const maxIndex = Math.ceil(newProductContainer.children.length / productsToShow) - 1;
  nextButton.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
  nextButton.style.pointerEvents = currentIndex >= maxIndex ? 'none' : 'auto';
}

updateDots();
updateButtonVisibility();

window.addEventListener('resize', function() {
  const scrollAmount = calculateScrollAmount();
  newProductContainer.scrollLeft = scrollAmount * currentIndex;
  updatedots();
  updateButtonVisibility();
});

// Optional: Swipe Support für 
let touchStartX = 0;
let touchEndX = 0;

newProductContainer.addEventListener('touchstart', function(e) {
  touchStartX = e.changedTouches[0].screenX;
});

newProductContainer.addEventListener('touchend', function(e) {
  touchEndX = e.changedTouches[0].screenX;
  if (touchEndX < touchStartX) {
    nextButton.click();
  } else if (touchEndX > touchStartX) {
    prevButton.click();
  }
});

}

// Bestsellers Slider
  const bestsellerContainer = document.querySelector('.best__sellers-container');
  const prevButtonBestseller = document.querySelector('.prev-button-bestseller');
  const nextButtonBestseller = document.querySelector('.next-button-bestseller');
  const dotsBestseller = document.querySelectorAll('.dot-bestseller');
  if (bestsellerContainer && prevButtonBestseller && nextButtonBestseller) {
    // restlicher Code
    
  function getProductsToShowBestseller() {
    if (window.innerWidth <= 480) {
      return 1;
    } else if (window.innerWidth <= 768) {
      return 2;
    } else {
      return 4;
    }
  }
  
  function calculateScrollAmountBestseller() {
    const productWidth = bestsellerContainer.querySelector('.best__sellers-item').offsetWidth;
    const gap = 20;
    return (productWidth + gap) * getProductsToShowBestseller();
  }
  
  let currentIndexBestseller = 0;
  
  function updateDotsBestseller() {
    const productsToShow = getProductsToShowBestseller();
    const maxIndex = Math.ceil(bestsellerContainer.children.length / productsToShow) - 1;
    
    dotsBestseller.forEach((dot, index) => {
      if (index <= maxIndex) {
        dot.style.display = 'block';
        dot.classList.toggle('active', index === currentIndexBestseller);
      } else {
        dot.style.display = 'none';
      }
    });
  }
  
  nextButtonBestseller.addEventListener('click', function() {
    const productsToShow = getProductsToShowBestseller();
    if ((currentIndexBestseller + 1) * productsToShow < bestsellerContainer.children.length) {
      currentIndexBestseller++;
      bestsellerContainer.scrollLeft = calculateScrollAmountBestseller() * currentIndexBestseller;
      updateDotsBestseller();
      updateButtonVisibilityBestseller();
    }
  });
  
  prevButtonBestseller.addEventListener('click', function() {
    if (currentIndexBestseller > 0) {
      currentIndexBestseller--;
      bestsellerContainer.scrollLeft = calculateScrollAmountBestseller() * currentIndexBestseller;
      updateDotsBestseller();
      updateButtonVisibilityBestseller();
    }
  });
  
  dotsBestseller.forEach((dot, index) => {
    dot.addEventListener('click', function() {
      currentIndexBestseller = index;
      bestsellerContainer.scrollLeft = calculateScrollAmountBestseller() * index;
      updateDotsBestseller();
      updateButtonVisibilityBestseller();
    });
  });
  
  function updateButtonVisibilityBestseller() {
    const productsToShow = getProductsToShowBestseller();
    prevButtonBestseller.style.opacity = currentIndexBestseller === 0 ? '0.5' : '1';
    prevButtonBestseller.style.pointerEvents = currentIndexBestseller === 0 ? 'none' : 'auto';
    
    const maxIndex = Math.ceil(bestsellerContainer.children.length / productsToShow) - 1;
    nextButtonBestseller.style.opacity = currentIndexBestseller >= maxIndex ? '0.5' : '1';
    nextButtonBestseller.style.pointerEvents = currentIndexBestseller >= maxIndex ? 'none' : 'auto';
  }
  
  updateDotsBestseller();
  updateButtonVisibilityBestseller();
  
  window.addEventListener('resize', function() {
    const scrollAmountBestseller = calculateScrollAmountBestseller();
    bestsellerContainer.scrollLeft = scrollAmountBestseller * currentIndexBestseller;
    updateDotsBestseller();
    updateButtonVisibilityBestseller();
  });
  
  
  // Optional: Swipe Support für Bestseller
  let touchStartXBestseller = 0;
  let touchEndXBestseller = 0;
  
  bestsellerContainer.addEventListener('touchstart', function(e) {
    touchStartXBestseller = e.changedTouches[0].screenX;
  });
  
  bestsellerContainer.addEventListener('touchend', function(e) {
    touchEndXBestseller = e.changedTouches[0].screenX;
    if (touchEndXBestseller < touchStartXBestseller) {
      nextButtonBestseller.click();
    } else if (touchEndXBestseller > touchStartXBestseller) {
      prevButtonBestseller.click();
    }
  });


  }
  
//
  renderCart();
//

  cartbtns = document.querySelectorAll(".add-to-cart-btn");
  if (cartbtns) {
    cartbtns.forEach(btn =>
      btn.addEventListener("click", () => {
        const product = btn.closest(".product-card");
        addToCart({
          id: product.dataset.id,
          title: product.dataset.title,
          price: parseInt(product.dataset.price),
          image: product.dataset.image,
          color: product.dataset.color,
          _slug: product.dataset.slug
        });
        console.log(product.dataset.id);
      })
    );
  }
  

  const section_animation = document.querySelectorAll(".animation");
  if (section_animation){
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
        }
      });
    }, {
      threshold: 0.3 // wenn 20% sichtbar, animieren
    });
  
    section_animation.forEach(image => observer.observe(image));
  
  }

// Manage product variants
const colorMap = {
  noir: "#000000",
  blanc: "#ffffff",
  rouge: "#ff0000",
  bleu: "#0000ff",
  vert: "#008000",
  gris: "#808080",
  beige: "#f5f5dc",
  doré: "#E8C06D",
  argenté: "#C0C0C0",
};
const variant = document.querySelector(".variant-color").innerHTML;
const variantColor = variant.replace("Couleur: ","");


const colorDots = document.querySelectorAll(".color-dot");
colorDots.forEach(dot => {
  dot.classList.remove("active");
  if (dot.dataset.id) {
    const productColorName = dot.dataset.id;
    const hexColor = colorMap[productColorName.toLowerCase()] || "#ccc";

    dot.style = `
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: ${hexColor};
      margin-bottom: 10px;
    `;  

    if (variantColor === dot.dataset.id) {
    dot.classList.add("active");
    console.log(variantColor);
    }
  }
})


// clone review under product title
  const prdTitle = document.getElementById("prd_title");
  const reviews = document.getElementById("rev_stars").innerHTML;
  const prd_el = document.createElement("div");
  prd_el.className = "clone_review";
  const rat_count = document.querySelector(".rating-count").innerHTML;
  prd_el.innerHTML = reviews+rat_count.replace(" avis","");
  rat_html = `<div  class="rating-count">${reviews} ${rat_count}</div>`;
  prdTitle.appendChild(prd_el);

//

const loadMoreBtn = document.getElementById('load-more-reviews');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const btn = this;
            const currentPage = parseInt(btn.getAttribute('data-page'));
            const productId = btn.getAttribute('data-product');
            const reviewsContainer = document.getElementById('reviews-container');
            const productSlug = btn.getAttribute('data-slug');
            
            // Show loading state
            btn.textContent = 'Chargement...';
            btn.disabled = true;
            
            // Make AJAX request
            fetch(`<?= ROOT_URL ?>products/<?= $slug ?>?ajax=1&page=${currentPage}&product_id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    // Add new reviews
                    data.reviews.forEach(review => {
                        const reviewHTML = `
                            <div class="comment-item">
                                <div class="comment-header">
                                    <span class="comment-author">${review.user_name}</span>
                                    <span class="comment-date">${review.created_at}</span>
                                </div>
                                
                                <div class="comment-rating">
                                    ${[1, 2, 3, 4, 5].map(i => 
                                        i <= review.rating ? 
                                            '<span class="star">★</span>' : 
                                            '<span class="star">☆</span>'
                                    ).join('')}
                                </div>
                                
                                ${review.comment ? `<div class="comment-text">${review.comment}</div>` : ''}
                            </div>
                        `;
                        reviewsContainer.insertAdjacentHTML('beforeend', reviewHTML);
                    });
                    
                    // Update button state
                    if (data.hasMore) {
                        btn.setAttribute('data-page', currentPage + 1);
                        btn.textContent = 'Afficher plus d\'avis';
                        btn.disabled = false;
                    } else {
                        // No more reviews to load
                        btn.remove();
                    }
                })
                .catch(error => {
                    console.error('Error loading reviews:', error);
                    btn.textContent = 'Erreur. Réessayer';
                    btn.disabled = false;
                });
        });
    }

//
});
//------------------------------------------------ show comment form -----------------------------------------------------------------------------//
const review = document.getElementById("review");
const form = document.querySelector(".rating-form");
review.addEventListener("click", () => {
  form.classList.toggle("active");
});
//---------------------------------------------------------  ----------------------------------------------------------------//
const zoomButton = document.querySelector('.zoom-button');
const zoomModal = document.getElementById('zoom-modal');
const zoomedImage = document.querySelector('.zoomed-image');
const closeModal = document.querySelector('.close-modal');
const mainImage = document.querySelector('.main-product-image');

zoomButton.addEventListener('click', () => {
  zoomedImage.src = mainImage.src; // Bild kopieren
  zoomModal.style.display = 'flex'; // Modal sichtbar machen
});

closeModal.addEventListener('click', () => {
  zoomModal.style.display = 'none';
});

// Optional: Modal schließen, wenn Benutzer auf den dunklen Bereich klickt
zoomModal.addEventListener('click', (e) => {
  if (e.target === zoomModal) {
    zoomModal.style.display = 'none';
  }
});
