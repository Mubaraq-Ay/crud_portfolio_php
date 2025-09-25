  // Preview Uploaded Image
  function previewImage(event) {
    const previewContainer = document.getElementById('imagePreview');
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        previewContainer.innerHTML = `<img src="${e.target.result}" alt="Image Preview" />`;
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}