<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>Page Title</title>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
</head>
<body>
     <input type="date" id="date">
     <input type="file" id="pdfFile">
     <button id="extractImages" type="submit">Extract</button>

<script>
function extractPDFPages(pdfUrl, outputFormat) {
  return new Promise(function (resolve, reject) {
    const loadingTask = pdfjsLib.getDocument(pdfUrl);

    loadingTask.promise.then(function (pdf) {
      const pageCount = pdf.numPages;
      const images = [];

      for (let pageNum = 1; pageNum <= pageCount; pageNum++) {
        pdf.getPage(pageNum).then(function (page) {
          const viewport = page.getViewport({ scale: 1.5 });
          const canvas = document.createElement('canvas');
          const context = canvas.getContext('2d');

          canvas.height = viewport.height;
          canvas.width = viewport.width;

          const renderTask = page.render({ canvasContext: context, viewport: viewport });

          renderTask.promise.then(function () {
            const imageData = canvas.toDataURL(outputFormat);
            images.push(imageData);

            if (images.length === pageCount) {
              resolve(images);
            }
          });
        });
      }
    }, function (error) {
      reject(error);
    });
  });
}

$('#extractImages').click(function() {
  var date = $('#date').val();
  var pdfFile = $('#pdfFile').val();
  // validate date and pdfFild 
  if (date == '') {
    alert('date field is required');
    return false;
  }
  if (pdfFile == '') {
    alert('pdf file field is require');
  }

    extractPDFPages('sample.pdf', 'image/png')
    .then(function (images) {
      const json = JSON.stringify(images);
      $.ajax({
        url:"save.php",
        type:'post',
        dataType:'json',
        data:{data:json,date:date,pdfFile:pdfFile},
        success:function (res) {
          if(res ==1){
            alert('Process Complete.');
            window.location.reload();
          }   
          if(res == 2){
            alert('Date already exists. Please change date.');
          }
        }
      });
    })
    .catch(function (error) {
      console.error('Error occurred while extracting PDF:', error);
    });
})
</script>
</body>
</html>