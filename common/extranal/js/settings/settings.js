$(document).ready(function () {
  "use strict";
  $(".flashmessage").delay(3000).fadeOut(100);
});

function copyToClipboard(elementId) {
  var copyText = document.getElementById(elementId);
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  document.execCommand("copy");
  
  toastr.success("URL copied to clipboard!");
}
