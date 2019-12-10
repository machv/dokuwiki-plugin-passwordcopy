
icobase = "../../plugins/passwordcopy/images/";

if(window.toolbar != undefined) {
  toolbar[toolbar.length] = {
    "type": "format",
      "title": "Password",
      "icon": icobase + "lock.png",
      "open": "<pass>",
      "close": "</pass>"
  };
}

var decodeHTML = function (html) {
	var txt = document.createElement('textarea');
	txt.innerHTML = html;
	return txt.value;
};

function togglePwd(id){
  let icon = document.getElementById(id + "_show");
  let el = document.getElementById(id);
  text = el.textContent;
  password = decodeHTML(el.dataset.password);
  mode = el.dataset.mode;

  if(mode == "obfuscated") {
    el.textContent = password;
    el.setAttribute("data-mode", "plain");
    icon.setAttribute("class", "pass-icon-eye-off");
  } else {
    el.textContent = "*".repeat(password.length);
    el.setAttribute("data-mode", "obfuscated");
    icon.setAttribute("class", "pass-icon-eye");
  }
}

function copyClip(id){
  var copyText = document.getElementById(id);
  text = decodeHTML(copyText.dataset.password);

  var el = document.createElement('textarea');
  el.value = text;
  document.body.appendChild(el);
  el.select();
  document.execCommand('copy');
  document.body.removeChild(el);

  el = document.createElement("span");
  el.setAttribute("class", "tooltiptext tooltip-top");
  el.innerHTML = "copied to clipboard";
  setTimeout(function(){
    el.parentNode.removeChild(el);
  }, 1000);
  copyText.parentNode.appendChild(el);
}

