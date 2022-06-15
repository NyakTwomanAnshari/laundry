const btnEdits = document.querySelectorAll(".btn-edit");
const id_paket = document.getElementById("edit_id_paket");
const id_outlet = document.getElementById("edit_id_outlet");
const jenis = document.getElementById("edit_jenis");
const harga = document.getElementById("edit_harga");
const optOutlet = document.querySelectorAll(".opt_edit_id_outlet");
const optJenis = document.querySelectorAll(".opt_edit_jenis");


btnEdits.forEach(function(e){
  e.addEventListener("click", function(el){
    let href = e.getAttribute("data-href");
    const xhr = new XMLHttpRequest();
    xhr.onload = function(e){
      if(xhr.status == 200){
        data = JSON.parse(xhr.responseText);
        id_paket.value = data.id_paket;
        harga.value = data.harga;
        optOutlet.forEach(function(e){
          if(e.value == data.id_outlet){
            e.setAttribute("selected", "on");
          }
        });
        optJenis.forEach(function(e){
          if(e.value == data.jenis){
            e.setAttribute("selected", "on");
          }
        });
      }else{
        alert("gagal mengambil data");
      }
    }
    xhr.open("GET", href, true);
    xhr.send();
  });
});


