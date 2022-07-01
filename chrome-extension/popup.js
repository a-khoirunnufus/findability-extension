console.log('POPUP START AT:', new Date().getSeconds());

// FIRST TIME RENDER BEGIN

document.addEventListener('DOMContentLoaded', updateStorageDataView);
updateScriptDataView();

// FIRST TIME RENDER END

// register content script button handler
const regCSBtn = document.getElementById('register-cs-btn');
regCSBtn.addEventListener('click', async () => {

  console.log('registering script..');
  // use this, script will take effect when page refresh
  // as the name it only registering script, script stay available when page reload
  chrome.scripting.registerContentScripts([{
    id: "1",
    runAt: 'document_idle',
    matches: ["http://localhost:3000/*", "https://drive.google.com/*"],
    js: ['content-script.js']
  }])

  console.log('executing script...');
  let [tab] = await chrome.tabs.query({ active: true, currentWindow: true });
  // use this, script immediately running, without page refresh
  // it will not store script data, getRegisteredScript == empty array
  // needed when first run
  chrome.scripting.executeScript({
    target: { tabId: tab.id },
    files: ['content-script.js'],
  });

  chrome.storage.local.set({ CSRegistered: true });
});

const unregCSBtn = document.getElementById('unregister-cs-btn');
unregCSBtn.addEventListener('click', async () => {
  console.log('unregistering script..');
  chrome.scripting.unregisterContentScripts();
  
  chrome.storage.local.set({ CSRegistered: false });
});

// listen storage changed event
chrome.storage.onChanged.addListener(function (changes, namespace) {
  // log storage changes
  console.log('STORAGE_CHANGED', changes, namespace);
  updateStorageDataView();
});

// update data display on popup
function updateStorageDataView() {
  chrome.storage.local.get(null, function(result) {
    document.getElementById("storage-data").innerText = JSON.stringify(result);
  });
}

document.getElementById('empty-storage-btn').addEventListener('click', () => {
  chrome.storage.local.clear();
})

document.getElementById('refresh-script-data').addEventListener('click', () => {
  updateScriptDataView();
});

function updateScriptDataView() {
  chrome.scripting.getRegisteredContentScripts((scripts) => {
    document.getElementById('script-count').innerText = scripts.length;

    let tempHtml = "";
    for (let script of scripts) {
      tempHtml += JSON.stringify(script.js) + "<br>";
    }
    document.getElementById('script-data').innerHTML = tempHtml;
  });
}