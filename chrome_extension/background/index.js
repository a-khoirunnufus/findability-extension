import setup from './setup.js';
import {hideSuggestionElm, showSuggestionElm} from '../content_scripts/suggestion_elm.js';
import {hideQuicknavElm, showQuicknavElm, addQuicknavElm} from '../content_scripts/quicknav_elm.js';

chrome.storage.local.set({
  'show_suggestion': false,
  'show_quicknav': true,
});

setup();

chrome.tabs.query({url: "https://drive.google.com/*"}, (tabs) => {
  const tabId = tabs[0].id;
  // SETUP SUGGESTION ELEMENT
  chrome.scripting.executeScript(
    {target: {tabId: tabId}, func: hideSuggestionElm}
  );

  // INIT QUICKNAV ELEMENT
  chrome.scripting.executeScript(
    {target: {tabId: tabId}, func: addQuicknavElm}
  )
});


// UPDATE GOOGLE DRIVE PAGE
chrome.storage.onChanged.addListener(function (changes, namespace) {
  if (changes.show_suggestion) {
    chrome.tabs.query({url: "https://drive.google.com/*"}, (tabs) => {
      const tabId = tabs[0].id;
      if (changes.show_suggestion.newValue == true) {
        chrome.scripting.executeScript(
          {target: {tabId: tabId}, func: showSuggestionElm}
        );
      } else {
        chrome.scripting.executeScript(
          {target: {tabId: tabId}, func: hideSuggestionElm}
        );
      }
    });
    return;
  }

  if(changes.show_quicknav) {
    chrome.tabs.query({url: "https://drive.google.com/*"}, (tabs) => {
      const tabId = tabs[0].id;
      if (changes.show_quicknav.newValue == true) {
        chrome.scripting.executeScript(
          {target: {tabId: tabId}, func: showQuicknavElm}
        );
      } else {
        chrome.scripting.executeScript(
          {target: {tabId: tabId}, func: hideQuicknavElm}
        );
      }
    });
  }
});

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

chrome.runtime.onMessage.addListener(
  function(request, sender, sendResponse) {
    if(request.type == 'BACKGROUND_LOG') {
      const time = request.time;
      const from = sender.tab ? `content_script(${sender.tab.url})` : 'extension';
      const message = request.message;
      console.log('LOG:', `[${time}]`, from, message);
    }
  }
);
/* LOGGING END */