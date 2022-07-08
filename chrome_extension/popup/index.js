import {
  listenDOMEvent, 
  loginBtnClickHandlerCreator
} from './dom_event.js';

window.addEventListener('DOMContentLoaded', async function(e) {
  
  const {
    showSuggestion, showQuicknav, gToken
  } =  await chrome.storage.local.get([
    'showSuggestion', 'showQuicknav', 'gToken',
  ]);

  // user verified if gToken value exist and not expired
  const isUserVerified = gToken.value && gToken.expiredAt && gToken.expiredAt > Math.floor(Date.now() / 1000);
  const tab = await getActiveTab();

  /**
   * INIT VIEW START
   */

  const suggestionElm = document.querySelector('#suggestion-elm-toggle');
  if (showSuggestion) { suggestionElm.setAttribute('checked', true) } 
  else { suggestionElm.removeAttribute('checked') }

  const quicknavElm = document.querySelector('#quicknav-elm-toggle');
  if (showQuicknav) { quicknavElm.setAttribute('checked', true) }
  else { quicknavElm.removeAttribute('checked') }
  
  /**
   * INIT VIEW END
   */

  /**
   * SETUP DOM LISTENER START
   */
  
  listenDOMEvent('#btn-login', 'click', loginBtnClickHandlerCreator(tab.id, () => {
    showSection('up-ready');
  }));

  suggestionElm.addEventListener('change', function(e) {
    if (e.target.checked) {
      // unregister content script: hide suggestion
      // register and execute content script: show suggestion
      chrome.storage.local.set({'showSuggestion': true});
    } else {
      // unregister content script: show suggestion
      // register and execute content script: hide suggestion
      chrome.storage.local.set({'showSuggestion': false});
    }
  });

  quicknavElm.addEventListener('change', function(e) {
    if (e.target.checked) {
      // unregister content script: hide quicknav
      // register and execute content script: show quicknav
      chrome.storage.local.set({'show_quicknav': true});
    } else {
      // unregister content script: show quicknav
      // register and execute content script: hide quicknav
      chrome.storage.local.set({'show_quicknav': false});
    }
  });

  /**
   * SETUP DOM LISTENER END
   */

  /**
   * POPUP PAGE ROUTING START
   */
  
  let page = undefined;
  if(tab.url.match("http://localhost:8080/*")) { page = 'USER_PORTAL' }
  else if(tab.url.match("https://drive.google.com/*")) { page = 'GOOGLE_DRIVE' }

  switch (page) {
    case 'USER_PORTAL':
      if(!isUserVerified) {
        showSection('up-login');
        break;
      }
      showSection('up-ready');
      break;
    
    case 'GOOGLE_DRIVE':
      if(!isUserVerified) {
        showSection('gd-login');
        break;
      }
      showSection('gd-ready');
      break;
    
    default:
      break;
  }
  
  /**
   * POPUP PAGE ROUTING END
   */

});

function getActiveTab() {
  return new Promise((resolve, reject) => {
    chrome.tabs.query({active: true}, (tabs) => {
      if(tabs.length == 1) {
        resolve(tabs[0]);
      } else {
        reject(false);
      }
    });
  })
}

function testFetch() {
  chrome.storage.local.get(['g_token'], function(result) {
    fetch('http://localhost:8081/file/index', {
      headers: { 'Authorization': 'Bearer '+result.g_token }
    })
      .then(res => res.json())
      .then(data => {
        console.log(data.data);
      });
  });
}

function showSection(id) {
  document.querySelectorAll('section').forEach(element => {
    element.classList.remove('show');
    if (element.id == id) {
      element.classList.add('show');
    }
  });
}

