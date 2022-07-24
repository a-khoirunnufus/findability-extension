import {
    bodyQuicknav,
    bodyUserTesting,
    bodyUserTestingCurrent,
    bodyUserTestingList,
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
  bodyUserTestingCurrent.classList.remove('show');
  bodyUserTestingList.classList.remove('show');
}

export {
  getAccessToken,
  resetStackView,
}