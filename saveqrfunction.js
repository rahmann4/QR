function saveAsPNG(qrCodeId) {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'qrcodes/' + qrCodeId + '.svg', true);
  xhr.responseType = 'blob';
  xhr.onload = function() {
    if (xhr.status === 200) {
      var blob = xhr.response;
      var url = URL.createObjectURL(blob);
      var img = new Image();
      img.onload = function() {
        var canvas = document.createElement('canvas');
        canvas.width = img.width;
        canvas.height = img.height;
        var ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, img.width, img.height);
        canvas.toBlob(function(blob) {
          var link = document.createElement('a');
          link.download = qrCodeId + '.png';
          link.href = URL.createObjectURL(blob);
          link.click();
          URL.revokeObjectURL(url);
        }, 'image/png');
      };
      img.src = url;
    }
  };
  xhr.send();
}

function saveAsSVG(qrCodeId) {
  var svgContent = document.getElementById("svgContent-" + qrCodeId);
  var canvas = document.createElement("canvas");
  var ctx = canvas.getContext("2d");
  var bbox = svgContent.getBBox();
  canvas.width = bbox.width;
  canvas.height = bbox.height;
  var data = new XMLSerializer().serializeToString(svgContent);
  var blob = new Blob([data], { type: "image/svg+xml;charset=utf-8" });
  var url = window.URL.createObjectURL(blob);

  var link = document.createElement("a");
  link.download = "qr_code.svg";
  link.href = url;
  link.click();

  window.URL.revokeObjectURL(url);
}