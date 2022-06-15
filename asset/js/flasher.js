
//alert('test');
const flashCRUD = document.getElementById("flash-crud");
if (flashCRUD != null) {
  const Title = flashCRUD.getAttribute("data-title");
  const Text = flashCRUD.getAttribute("data-text");
  const Icon = flashCRUD.getAttribute("data-icon");

  let text = Text.replace(/-/g, " ");
  
  Swal.fire({
    icon: Icon,
    text : text,
    title: Title
  });
}

