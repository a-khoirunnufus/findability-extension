// console.log('auth content script loaded');
if(document.readyState === "complete" || document.readyState === "interactive") {
  let email = document.querySelector('div.gb_de').lastChild.innerText;
  chrome.runtime.sendMessage({logged_email: email});
}else {
  window.addEventListener("DOMContentLoaded", () => {
    let email = document.querySelector('div.gb_de').lastChild.innerText;
    chrome.runtime.sendMessage({logged_email: email});
  });
}