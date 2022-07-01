// console.log('POPUP START AT:', new Date().getSeconds());

chrome.tabs.query({active: true, currentWindow: true}, function(tabs) {
  chrome.scripting.executeScript({
    target: { tabId: tabs[0].id },
    files: ['auth/auth_content_script.js'],
  });
});

// receive message from content script
// receive logged email
chrome.runtime.onMessage.addListener(
  function(request, sender, sendResponse) {
    document.querySelector('#btn-signin').innerText = 'Masuk ke QuickNav: '+request.logged_email;
  }
);

// create window for sign in with google
const fullWidth = 1327;
const fullHeight = 741;

document.querySelector('#btn-signin').addEventListener('click', function() {
  const width = 500;
  const height = 578;

  chrome.windows.create(
    {
      focused: true,
      width: width,
      height: height, 
      left: Math.floor((fullWidth/2) - (width/2)), 
      top: Math.floor((fullHeight/2) - (height/2)), 
      type: 'popup', 
      url: 'http://localhost:8080/auth/signin'
    },
    function(window) {
      const newTabId = window.tabs[0].id;

      chrome.tabs.onUpdated.addListener(
        (tabId, changeInfo, currentTab) => {
          if (tabId == newTabId && changeInfo.url == 'http://localhost:8080/auth/post-signin') {
            console.log('post signin page');
            chrome.scripting.executeScript({
              target: { tabId: newTabId },
              files: ['auth/get_token.js']
            });
          }
        }
      )
    }
  );
});