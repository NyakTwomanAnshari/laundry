function confirmHapus(e){
  const href = e.getAttribute("data-href");
  const text = e.getAttribute("data-text");
  Swal.fire({
    title: 'Yakin??',
    text: text,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, do It!!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = href;
    }
  })
}

function confirmLogout(e){
  const href = e.getAttribute("data-href");
  Swal.fire({
    title: 'Yakin Ingin Keluar??',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = href;
    }
  })
}