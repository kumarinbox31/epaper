$('#page').change(function () {
   var url = $(this).find(':selected').attr('data-url');
   window.location.href= url; 
});