let index = 0;
displayImages();
function displayImages() {
  let i;
  const image = document.getElementsByClassName("image");
  for (i = 0; i < image.length; i++) {
    image[i].style.display = "none";
  }
  index++;
  if (index > image.length) {
    index = 1;
  }
  image[index-1].style.display = "block";
  setTimeout(displayImages, 2000); 
}


