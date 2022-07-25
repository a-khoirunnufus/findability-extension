import setup from './setup.js';

// TODO: before reload extension, clear all data

// when extension first installed / reloaded
chrome.runtime.onInstalled.addListener(() => {
  // set starting storage data
  chrome.storage.local.set({
    'lastPopupEvent': {
      'type': null,
      'detail': null,
    },
    'showQuicknav': false,
    'activeTask': {
      'itemId': null,
      'status': null,
    },
    'taskLog': [],
    // task log format:
    // {
    //   action: 'navigate',
    //   object: 'https://drive.google.com/...',
    //   time: new Date(),
    // }
  });

  // register content script
  // chrome.scripting.registerContentScripts(
  //   [
  //     {
  //       id: 'gdcomponent-hide',
  //       js: [ 
  //         'content_scripts/googledrive/filelist_hide.js',
  //         'content_scripts/googledrive/searchbar_hide.js' 
  //       ],
  //       matches: [ 'https://drive.google.com/*' ],
  //       runAt: 'document_end',
  //     },
  //     {
  //       id: 'quicknav-main',
  //       css: [ 'content_scripts/quicknav/main.css' ],
  //       js: [ 'content_scripts/quicknav/main.js' ],
  //       matches: [ 'https://drive.google.com/*' ],
  //       runAt: 'document_end',
  //     },
  //   ]
  // );

  /* DEBUGGER */
  const extensionId = "bimmbnifpklehmnbcnkfkgmanckjceea"
  chrome.tabs.create({
    active: false,
    url: `chrome-extension://${extensionId}/debugger/index.html`,
  });
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

// LISTEN MESSAGE
chrome.runtime.onMessage.addListener(
  async function(request, sender, sendResponse) {
    if(request.code === 'FINAL_TASK_LOG') {
      console.log('final task log', request.data);

      const logs = request.data.logs;
      const taskItemId = request.data.taskItemId;
      
      // send log to server as json
      const accessToken = await getAccessToken();
      let res = await fetch(
        'http://localhost:8080/api/item/log?task_item_id='+taskItemId
          +'&logs='+JSON.stringify(logs),
        {
          method: 'GET',
          headers: { 'Authorization': 'Basic ' + btoa(`${accessToken}:password`) },
        },
      );
      res = await res.json();
      console.log(res);
    }
  }
);

function getAccessToken() {
  return new Promise((resolve, reject) => {
    chrome.cookies.get(
      { name: 'access_token', url: 'http://localhost:8080' },
      (cookie) => {
        resolve(cookie.value);
      }
    );
  });
}

// DETECT URL CHANGES START
// async function getCurrentTab() {
//   let queryOptions = { active: true, currentWindow: true };
//   let [tab] = await chrome.tabs.query(queryOptions);
//   return tab;
// }

chrome.tabs.onUpdated.addListener(
  async (tabId, changeInfo, tab) => {
    
    const {activeTask} = await chrome.storage.local.get(['activeTask']);

    if (activeTask.itemId && activeTask.status == 'running') {

      if (tabId == tab.id && changeInfo.url) {
        let {taskLog} = await chrome.storage.local.get(['taskLog']);

        taskLog.push({
          action: 'NAVIGATE',
          object: changeInfo.url,
          time: Math.floor(new Date().getTime()/1000.0),
        });

        chrome.storage.local.set({ taskLog: taskLog });
      }

    }
  }
)
// DETECT URL CHANGES END



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