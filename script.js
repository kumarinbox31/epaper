$('#page').change(function () {
   var url = $(this).find(':selected').attr('data-url');
   window.location.href= url; 
});

$('#clipBtn').click(function () {
   const image = document.getElementById('image');
   const cropper = new Cropper(image, {
   aspectRatio: 4 / 2,
   multiple:true,
   crop(event) {
      console.log(event.detail.x);
      console.log(event.detail.y);
      console.log(event.detail.width);
      console.log(event.detail.height);
      console.log(event.detail.rotate);
      console.log(event.detail.scaleX);
      console.log(event.detail.scaleY);
   },
   
   });   


   
})
