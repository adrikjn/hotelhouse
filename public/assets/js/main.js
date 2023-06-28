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

