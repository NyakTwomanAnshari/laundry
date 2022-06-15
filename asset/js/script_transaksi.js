
function getDataOutlet(e){

  const optOutlet = document.querySelectorAll(".opt-outlet");
  const selectOutlet = document.getElementById("select_outlet");
  const href = e.getAttribute("data-href") + e.value;
  if(e.value != 0 || e.value != ""){
    const xhr = new XMLHttpRequest();
    xhr.onload = function(e){
      if(xhr.status == 200){
        data = JSON.parse(xhr.responseText);
        optOutlet.forEach(function(ele){
          ele.removeAttribute("selected");
          if(ele.value == data.id_outlet){
            ele.setAttribute("selected", "");
          }
        });
        getDataPaket(selectOutlet);
      }else{
        alert("gagal meload data paket")
      }
    }
    xhr.open("GET", href, true);
    xhr.send();
  }
}

function getDataPaket(e){
  const selectPaket = document.getElementById("select_paket");
  const infoKetersediaanPaket = document.getElementById("InfoKetersediaanPaket");
  const href = e.getAttribute("data-href") + e.value;
  infoKetersediaanPaket.innerHTML = "";
  if(e.value != 0 || e.value != ""){
    const xhr = new XMLHttpRequest();
    xhr.onload = function(e){
      if(xhr.status == 200){
        data = JSON.parse(xhr.responseText);
        selectPaket.innerHTML = data.opt;
        if(data.sts == false){
          infoKetersediaanPaket.innerHTML = "<b class='text-danger'>Tidak Ada PAKET untuk Outlet ini. Mohon untuk menambah data PAKET terlebih dahulu!!</b>"
        }
        setTotalHarga();
      }else{
        alert("gagal meload data paket")
      }
    }
    xhr.open("GET", href, true);
    xhr.send();
  }
}

function setTotalHarga()
{
  const selectPaket = document.getElementById("select_paket");
  const jmlh_biaya = document.getElementById("total_bayar");
  const berat = document.getElementById("berat");
  let harga = selectPaket.options[selectPaket.selectedIndex].getAttribute('data-harga');
  
  let vBerat = parseFloat(berat.value);
  if(vBerat > 0){
    vBerat = vBerat;
  }else{
    vBerat = 0;
  }
  
  let total = harga * vBerat;
  jmlh_biaya.value = "Rp. " + formatRupiah(total)
}
function formatRupiah(bilangan){
  var	number_string = bilangan.toString(),
 	sisa 	= number_string.length % 3,
  rupiah 	= number_string.substr(0, sisa),
  ribuan 	= number_string.substr(sisa).match(/\d{3}/g);

  if (ribuan) {
   separator = sisa ? '.' : '';
 	 rupiah += separator + ribuan.join('.');
  }
  return rupiah;
}


