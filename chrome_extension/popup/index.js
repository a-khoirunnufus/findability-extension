window.addEventListener('DOMContentLoaded', async function(e) {
  
  const notificationElm = document.querySelector('.notification');
  const btnShowElm = document.querySelector('#btn-show');
  const btnHideElm = document.querySelector('#btn-hide');
    
  [btnShowElm, btnHideElm].forEach(function(elm) {
    elm.addEventListener('click', () => {
      if(elm.id == 'btn-show') {
        chrome.storage.local.set({showQuicknav: true});
        statusTextElm.innerText = 'Quicknav Diaktifkan';
      } else if(elm.id == 'btn-hide') {
        chrome.storage.local.set({showQuicknav: false});
        statusTextElm.innerText = 'Quicknav Dimatikan'
      }
      notificationElm.classList.add('show');
    });
  });
    
  const { showQuicknav } =  await chrome.storage.local.get(['showQuicknav']);

  const statusTextElm = document.querySelector('#status-text');
  if (showQuicknav) { statusTextElm.innerText = 'Quicknav Diaktifkan' }
  else { statusTextElm.innerText = 'Quicknav Dimatikan' }

});

