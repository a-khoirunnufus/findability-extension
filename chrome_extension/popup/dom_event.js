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
    .then(({g_token}) => {
      chrome.storage.local.set({
        gToken: {
          value: g_token.value,
          expiredAt: g_token.expired_at,
        }
      });
    });
}

export { listenDOMEvent, loginBtnClickHandlerCreator }