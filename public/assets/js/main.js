const logoImpression = document.getElementById('logo-impression');
  logoImpression.addEventListener('click', () => {
    window.print();
  });


  const lienPartage = document.getElementById('lien-partage');
  lienPartage.addEventListener('click', (event) => {
    event.preventDefault();
    const url = encodeURIComponent(window.location.href);
    const titre = encodeURIComponent(document.title);
    const partageURL = `https://example.com/partager?url=${url}&titre=${titre}`;
    window.open(partageURL, '_blank');
  });

  const spaLinks = document.querySelectorAll('.spa-link');
  const spaSections = document.querySelectorAll('.spa-section');
  
  // Ajouter la classe "show" à la première section par défaut
  spaSections[0].classList.add('show');
  
  spaLinks.forEach((link, index) => {
    link.addEventListener('click', (event) => {
      event.preventDefault();
      const target = link.dataset.target;
  
      spaSections.forEach(section => {
        section.classList.remove('show');
      });
  
      const targetSection = document.getElementById(target);
      targetSection.classList.add('show');
    });
  });
  