$('#page').change(function () {
   var url = $(this).find(':selected').attr('data-url');
   window.location.href = url;
});

$('#clipBtn').click(function () {
   const image = document.getElementById('image');
   var cropper = new Cropper(image, {
      aspectRatio: 4 / 2,
      multiple: true,
      crop(event) {
         // console.log(event.detail.x);
         // console.log(event.detail.y);
         // console.log(event.detail.width);
         // console.log(event.detail.height);
         // console.log(event.detail.rotate);
         // console.log(event.detail.scaleX);
         // console.log(event.detail.scaleY);
      },

   });
   var buttons = `<a id="closeClip" class="btn btn-danger" style="position: absolute;top: -2.5rem;">Close</a>`;
   var cropBoxElement = document.querySelector('.cropper-crop-box');
   $('.cropper-container').append(buttons);

   // console.log(cropBoxData);

         
      $('#closeClip').click(function () {
         cropper.destroy();
         $('#closeClip').hide();
      })

})
