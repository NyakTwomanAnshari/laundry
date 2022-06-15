
function preview(){
  const fotoProfile = document.getElementById("foto_profile");
  const imgPreview = document.getElementById("img_preview");
  
  const fileFoto = new FileReader();
  fileFoto.readAsDataURL(fotoProfile.files[0]);
  
  fileFoto.onload = function(e){
    imgPreview.src = e.target.result;
  };

}
