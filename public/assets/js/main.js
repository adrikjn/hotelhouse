const logoImpression = document.getElementById("logo-impression");
logoImpression.addEventListener("click", () => {
  window.print();
});

const lienPartage = document.getElementById("lien-partage");
const urlCopie = document.getElementById("url-copie");
lienPartage.addEventListener("click", (event) => {
  event.preventDefault();
  const url = window.location.href;
  urlCopie.value = url;
  urlCopie.select();
  document.execCommand("copy");
  alert(
    "Lien copié avec succès ! Vous pouvez le coller et le partager avec vos amis."
  );
});

const spaLinks = document.querySelectorAll(".spa-link");
const spaSections = document.querySelectorAll(".spa-section");

// Ajouter la classe "show" à la première section par défaut
spaSections[0].classList.add("show");

spaLinks.forEach((link, index) => {
  link.addEventListener("click", (event) => {
    event.preventDefault();
    const target = link.dataset.target;

    spaSections.forEach((section) => {
      section.classList.remove("show");
    });

    const targetSection = document.getElementById(target);
    targetSection.classList.add("show");
  });
});
