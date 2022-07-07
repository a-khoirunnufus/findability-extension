fetch('http://localhost:8080/auth/get-g-token')
  .then(res => res.json())
  .then(data => {
    chrome.storage.local.set({g_token: data.g_token});
  });

