function listenDOMEvent(sel, type, handler) {
  document.querySelector(sel).addEventListener(type, handler);
}

function loginBtnClickHandlerCreator(tabId, cb) {
  return () => {
    chrome.scripting.executeScript(
      { target: {tabId: tabId}, func: storeGToken},
      () => { cb() }
    );
  }
}

function storeGToken() {
  fetch('http://localhost:8080/auth/get-g-token')
    .then(res => res.json())
    .then(data => {
      chrome.storage.local.set({g_token: data.g_token});
    });
}

export { listenDOMEvent, loginBtnClickHandlerCreator }