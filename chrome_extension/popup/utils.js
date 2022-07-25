import {
    bodyQuicknav,
    bodyUserTesting,
    bodyUserTestingActiveTask,
    bodyUserTestingTaskViewer,
  } from './elements.js';

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

function resetStackView() {
  bodyQuicknav.classList.remove('show');
  bodyUserTesting.classList.remove('show');
  bodyUserTestingActiveTask.classList.remove('show');
  bodyUserTestingTaskViewer.classList.remove('show');
}

async function getCurrentTab() {
  let queryOptions = { active: true, lastFocusedWindow: true };
  // `tab` will either be a `tabs.Tab` instance or `undefined`.
  let [tab] = await chrome.tabs.query(queryOptions);
  return tab;
}

export {
  getAccessToken,
  resetStackView,
  getCurrentTab,
}