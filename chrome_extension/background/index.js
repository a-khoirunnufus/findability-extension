import setup from './setup.js';

// when extension first installed / reloaded
chrome.runtime.onInstalled.addListener(() => {
  // set starting storage data
  chrome.storage.local.set({
    'showQuicknav': true,
    'popup_qnOpen': false,
    'popup_utOpen': true,
    'popup_utCurrentOpen': false,
    'popup_utListOpen': true,
  });

  // register content script
  chrome.scripting.registerContentScripts(
    [
      {
        id: 'gdcomponent-hide',
        js: [ 
          'content_scripts/googledrive/filelist_hide.js',
          'content_scripts/googledrive/searchbar_hide.js' 
        ],
        matches: [ 'https://drive.google.com/*' ],
        runAt: 'document_end',
      },
      {
        id: 'quicknav-main',
        css: [ 'content_scripts/quicknav/main.css' ],
        js: [ 'content_scripts/quicknav/main.js' ],
        matches: [ 'https://drive.google.com/*' ],
        runAt: 'document_end',
      },
    ]
  );
});

setup();

// storage change event
chrome.storage.onChanged.addListener(function (changes, namespace) {
  if (changes.showQuicknav) {
    if (changes.showQuicknav.newValue === true) {
      chrome.scripting.registerContentScripts([
        {
          id: 'gdcomponent-hide',
          js: [ 
            'content_scripts/googledrive/filelist_hide.js',
            'content_scripts/googledrive/searchbar_hide.js' 
          ],
          matches: [ 'https://drive.google.com/*' ],
          runAt: 'document_end',
        },
        {
          id: 'quicknav-main',
          css: [ 'content_scripts/quicknav/main.css' ],
          js: [ 'content_scripts/quicknav/main.js' ],
          matches: [ 'https://drive.google.com/*' ],
          runAt: 'document_end',
        },
      ]);
    } else {
      chrome.scripting.unregisterContentScripts({
        ids: ['quicknav-main', 'gdcomponent-hide'],
      })
    }
  }
})

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
chrome.tabs.create({
  active: false,
  url: `chrome-extension://${extensionId}/debugger/index.html`,
});

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