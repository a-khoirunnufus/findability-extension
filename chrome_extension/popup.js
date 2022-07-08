import {loginHtml, loginInstructionHtml} from './popup_pages/login.js';

const main = document.querySelector('main');

const getActiveTab = () => {
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

window.addEventListener('DOMContentLoaded', function(e) {
  
  chrome.storage.local.get(['g_token'], function(result) {
    // if g_token not exist
    if(result.g_token === undefined) {
      getActiveTab()
        .then(tab => {
          // if url == 'http://localhost:8080', show login button
          if(tab.url.match('http://localhost:8080/*')) {
            main.innerHTML = `<button id="btn-login">Login</button>`;
            document.querySelector('#btn-login').addEventListener('click', () => {
              chrome.scripting.executeScript(
                {
                  target: {tabId: tab.id},
                  files: ['content_scripts/store_g_token.js'],
                },
                () => {
                  // finish execute script
                  main.innerHTML = '<p>Berhasil login</p>';    
                }
              );
            });
          }

          // if url == 'https://drive.google.com', show instruction for login in user_portal
          else if(tab.url.match('https://drive.google.com/*')) {
            main.innerHTML = '<p>Silahkah login pada halaman user portal.</p>';
          }
        });

      return;
    }

    // if g_token exist, show information about user already logged in
    getActiveTab()
      .then(tab => {
        // if url == 'http://localhost:8080', show login button
        if(tab.url.match('http://localhost:8080/*')) {
          main.innerHTML = '<p>Ekstensi siap digunakan.</p>';
        }
    
        // if url == 'https://drive.google.com', show instruction for login in user_portal
        else if(tab.url.match('https://drive.google.com/*')) {
          chrome.scripting.executeScript({
              target: {tabId: tab.id},
              func: testFetch,
          });

          main.innerHTML = `<p>Siap untuk melakukan navigasi</p>`;
        }
      })
  });

});

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

