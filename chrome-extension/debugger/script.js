document.addEventListener('DOMContentLoaded', updateStorageDataView);

// listen storage changed event
chrome.storage.onChanged.addListener(function (changes, namespace) {
  updateStorageDataView();
});

document.getElementById('empty-storage-btn').addEventListener('click', () => {
  chrome.storage.local.clear();
})

// update data display on popup
function updateStorageDataView() {
  chrome.storage.local.get(null, function(result) {
    document.getElementById("storage-data").innerText = JSON.stringify(result);
  });
}