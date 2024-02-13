function saveAsPNG() {
  var svgContent = document.getElementById("svgContent");
  var canvas = document.createElement("canvas");
  var ctx = canvas.getContext("2d");
  var bbox = svgContent.getBBox();
  canvas.width = bbox.width;
  canvas.height = bbox.height;
  var data = new XMLSerializer().serializeToString(svgContent);
  var DOMURL = window.URL || window.webkitURL || window;
  var img = new Image();
  var svgBlob = new Blob([data], { type: "image/svg+xml;charset=utf-8" });
  var url = DOMURL.createObjectURL(svgBlob);

  img.onload = function () {
    ctx.drawImage(img, 0, 0);
    DOMURL.revokeObjectURL(url);

    var imgURI = canvas.toDataURL("image/png");
    var fileName = "qr_code.png";

    var link = document.createElement("a");
    link.download = fileName;
    link.href = imgURI;
    link.click();
  };

  img.src = url;
}

function saveAsSVG() {
  var svgContent = document.getElementById("svgContent");
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
