const g_token = document.cookie
  .split('; ')
  .find(row => row.startsWith('g_token='))
  ?.split('=')[1];

chrome.storage.local.set({g_token: g_token}, function() {
  console.log('g_token is set to ' + g_token);
});