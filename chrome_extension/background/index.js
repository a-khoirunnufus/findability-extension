import setup from './setup.js';
import {hideSuggestionElm, showSuggestionElm} from '../content_scripts/suggestion_elm.js';
import {hideQuicknavElm, showQuicknavElm, addQuicknavElm} from '../content_scripts/quicknav_elm.js';

// when extension first installed / reloaded
chrome.runtime.onInstalled.addListener(() => {
  // set starting storage data
  chrome.storage.local.set({
    'showSuggestion': true,
    'showQuicknav': false,
    'gToken': {
      'value': undefined,
      'expiredAt': undefined   
    }
  });

  // register content script
  chrome.scripting.registerContentScript(
    [
      {
        id: 'quicknav-main',
        js: [ 'content_scripts/quicknav.js' ],
        matches: [ 'https://drive.google.com/*' ],
      }
    ]
  );
});

// setup();

// !! MOVE THIS TO POPUP PAGE
// UPDATE GOOGLE DRIVE PAGE
// chrome.storage.onChanged.addListener(function (changes, namespace) {
//   if (changes.show_suggestion) {
//     chrome.tabs.query({url: "https://drive.google.com/*"}, (tabs) => {
//       const tabId = tabs[0].id;
//       if (changes.show_suggestion.newValue == true) {
//         chrome.scripting.executeScript(
//           {target: {tabId: tabId}, func: showSuggestionElm}
//         );
//       } else {
//         chrome.scripting.executeScript(
//           {target: {tabId: tabId}, func: hideSuggestionElm}
//         );
//       }
//     });
//     return;
//   }

//   if(changes.show_quicknav) {
//     chrome.tabs.query({url: "https://drive.google.com/*"}, (tabs) => {
//       const tabId = tabs[0].id;
//       if (changes.show_quicknav.newValue == true) {
//         chrome.scripting.executeScript(
//           {target: {tabId: tabId}, func: showQuicknavElm}
//         );
//       } else {
//         chrome.scripting.executeScript(
//           {target: {tabId: tabId}, func: hideQuicknavElm}
//         );
//       }
//     });
//   }
// });


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