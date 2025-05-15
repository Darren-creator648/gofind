 // Disparaît après 3 secondes
 const message = document.getElementById('successMessage');
 if (message) {
   setTimeout(() => {
     message.style.display = 'none';
   }, 3000); // 3000 ms = 3 sec
 }
 // Supprime le ?success=1 de l'URL sans recharger
if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.pathname);
}
function previewImage(event) {
  const reader = new FileReader();
  reader.onload = function () {
    const output = document.getElementById('apercuImage');
    output.src = reader.result;
    output.style.display = 'block';
  };
  reader.readAsDataURL(event.target.files[0]);
}
