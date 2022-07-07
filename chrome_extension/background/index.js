import setup from './setup.js';

setup();

// check if the user is logged in
  // check the storage, is fex_token exist in storage
// chrome.storage.local.get(['g_token'], function(result) {
//   // if not exists, show auth popup, in auth popup user can login using signin button
//   if (result.g_token == undefined) {
//     chrome.action.setPopup(
//       { popup: 'auth/auth_popup.html' },
//     );
//   // if exist show main popup
//   } else {
//     chrome.action.setPopup(
//       { popup: 'popup.html' },
//     );
//   }
// });

// chrome.storage.onChanged.addListener(function (changes, namespace) {
//   if (changes.g_token.newValue) {
//     chrome.action.setPopup(
//       { popup: 'popup.html' },
//     );
//   } else {
//     chrome.action.setPopup(
//       { popup: 'auth/auth_popup.html' },
//     );
//   }
// });


// check database, is access_token exist
// if not, open a new (small) window to viewing oauth user consent

// because fex_token exist in storage and access_token exist in database
// show a main popup (popup that viewed when user do navigation)

/* TEMPORARY COMMENT
// LISTEN MESSAGE
chrome.runtime.onMessage.addListener(
  function(request, sender, sendResponse) {
    console.log(sender.tab ?
                "from a content script:" + sender.tab.url :
                "from the extension");

    // message from content script
    if (request.greeting === "hello")
      sendResponse({farewell: "goodbye"});
      console.log('file clicked:', request.filename);
  }
);

// DETECT URL CHANGES START
async function getCurrentTab() {
  let queryOptions = { active: true, currentWindow: true };
  let [tab] = await chrome.tabs.query(queryOptions);
  return tab;
}

// todo: move this to store
let tab = getCurrentTab();

chrome.tabs.onUpdated.addListener(
  (tabId, changeInfo, currentTab) => {
    if (changeInfo.url) {
      console.log('BACKGROUND: URL CHANGED', {
        "old url": tab.url,
        "new url": changeInfo.url
      });

      // send message to content script
      chrome.tabs.query({active: true, currentWindow: true}, function(tabs) {
        chrome.tabs.sendMessage(
          tabs[0].id, 
          { status: "URL_CHANGED", data: {
            "old_url": tab.url,
            "new_url": changeInfo.url
          }}, 
          function(response) {
            console.log(response);
          }
        );
      });

      tab = currentTab;
    }
  }
)
// DETECT URL CHANGES END
*/

/* DEBUGGER */
const extensionId = "bimmbnifpklehmnbcnkfkgmanckjceea"
chrome.tabs.create(
  {url: `chrome-extension://${extensionId}/debugger/index.html`}
);

/* LOGGING START */
chrome.storage.onChanged.addListener(function (changes, namespace) {
  for (let [key, { oldValue, newValue }] of Object.entries(changes)) {
    console.log(
      `Storage key "${key}" in namespace "${namespace}" changed.`
      ,`Old value was "${oldValue}", new value is "${newValue}".`
    );
  }
});
/* LOGGING END */