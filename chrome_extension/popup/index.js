import {
  listenDOMEvent, 
  loginBtnClickHandlerCreator
} from './dom_event.js';

window.addEventListener('DOMContentLoaded', async function(e) {
  
  const g_token =  await chrome.storage.local.get(['g_token']);
  const isUserLoggedIn = g_token.g_token !== undefined;
  const tab = await getActiveTab();

  // setup dom listener
  listenDOMEvent('#btn-login', 'click', loginBtnClickHandlerCreator(tab.id, () => {
    showSection('up-ready');
  }));

  // suggestion element
  const show_suggestion = await chrome.storage.local.get(['show_suggestion']);
  const suggestionElm = document.querySelector('#suggestion-elm-toggle');
  if (show_suggestion.show_suggestion) {
    suggestionElm.setAttribute('checked', true);
  } else {
    suggestionElm.removeAttribute('checked');
  }
  suggestionElm.addEventListener('change', function(e) {
    if (e.target.checked) {
      chrome.storage.local.set({'show_suggestion': true});
    } else {
      chrome.storage.local.set({'show_suggestion': false});
    }
  });

  // suggestion element
  const show_quicknav = await chrome.storage.local.get(['show_quicknav']);
  const quicknavElm = document.querySelector('#quicknav-elm-toggle');
  if (show_quicknav.show_quicknav) {
    quicknavElm.setAttribute('checked', true);
  } else {
    quicknavElm.removeAttribute('checked');
  }
  quicknavElm.addEventListener('change', function(e) {
    if (e.target.checked) {
      chrome.storage.local.set({'show_quicknav': true});
    } else {
      chrome.storage.local.set({'show_quicknav': false});
    }
  });

  let page = undefined;
  if(tab.url.match("http://localhost:8080/*")) { page = 'USER_PORTAL' }
  else if(tab.url.match("https://drive.google.com/*")) { page = 'GOOGLE_DRIVE' }

  console.log('page:', page);
  console.log('is user logged in:', isUserLoggedIn);

  switch (page) {
    case 'USER_PORTAL':
      if(!isUserLoggedIn) {
        showSection('up-login');
        break;
      }
      showSection('up-ready');
      break;
    
    case 'GOOGLE_DRIVE':
      if(!isUserLoggedIn) {
        showSection('gd-login');
        break;
      }
      showSection('gd-ready');
      break;
    
    default:
      break;
  }
  
  // chrome.storage.local.get(['g_token'], function(result) {
  //   // if g_token not exist
  //   if(result.g_token === undefined) {
  //     getActiveTab()
  //       .then(tab => {
  //         // if url == 'http://localhost:8080', show login button
  //         if(tab.url.match('http://localhost:8080/*')) {
  //           main.innerHTML = `<button id="btn-login">Login</button>`;
  //           document.querySelector('#btn-login').addEventListener('click', () => {
  //             chrome.scripting.executeScript(
  //               {
  //                 target: {tabId: tab.id},
  //                 files: ['content_scripts/store_g_token.js'],
  //               },
  //               () => {
  //                 // finish execute script
  //                 main.innerHTML = '<p>Berhasil login</p>';    
  //               }
  //             );
  //           });
  //         }

  //         // if url == 'https://drive.google.com', show instruction for login in user_portal
  //         else if(tab.url.match('https://drive.google.com/*')) {
  //           main.innerHTML = '<p>Silahkah login pada halaman user portal.</p>';
  //         }
  //       });

  //     return;
  //   }

  //   // if g_token exist, show information about user already logged in
  //   getActiveTab()
  //     .then(tab => {
  //       // if url == 'http://localhost:8080', show login button
  //       if(tab.url.match('http://localhost:8080/*')) {
  //         main.innerHTML = '<p>Ekstensi siap digunakan.</p>';
  //       }
    
  //       // if url == 'https://drive.google.com', show instruction for login in user_portal
  //       else if(tab.url.match('https://drive.google.com/*')) {
  //         chrome.scripting.executeScript({
  //             target: {tabId: tab.id},
  //             func: testFetch,
  //         });

  //         main.innerHTML = `<p>Siap untuk melakukan navigasi</p>`;
  //       }
  //     })
  // });

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

