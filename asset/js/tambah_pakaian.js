
const btnTambah = document.getElementById("btnTambah");
const bodyTable = document.getElementById("bodyTable");

btnTambah.addEventListener("click", function(e) {

  const inputJenis = document.createElement('input');
  inputJenis.className = "form-control abc";
  inputJenis.name = "jenis_pakaian[]";
  inputJenis.setAttribute("required", "");
  inputJenis.setAttribute("placeholder", "Masukkan Jenis Pakaian");

  const td1 = document.createElement("td");
  td1.appendChild(inputJenis);

  const inputHarga = document.createElement('input');
  inputHarga.className = "form-control abc";
  inputHarga.name = "jumlah[]";
  inputHarga.type = "number";
  inputHarga.setAttribute("required", "");
  inputHarga.setAttribute("placeholder", "Masukan Jumlah Pakaian");

  const td2 = document.createElement("td");
  td2.appendChild(inputHarga);
  

  const btnHapus = document.createElement("button");
  btnHapus.className = "btn btn-danger btn-hapus-list";
  btnHapus.textContent = "hapus";

  const td3 = document.createElement("td");
  td3.className = "text-center";
  td3.appendChild(btnHapus);

  const tr = document.createElement("tr")

  tr.appendChild(td1);
  tr.appendChild(td2);
  tr.appendChild(td3);

  bodyTable.appendChild(tr);

})

bodyTable.addEventListener("click", function(e) {
  if (e.target.classList.contains("btn-hapus-list")) {
    const elements = e.target.parentElement.parentElement;
    elements.remove();
  }
})