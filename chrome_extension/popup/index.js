window.addEventListener('DOMContentLoaded', async function(e) {
  
  // const notificationElm = document.querySelector('.notification');
  // const btnShowElm = document.querySelector('#btn-show');
  // const btnHideElm = document.querySelector('#btn-hide');
    
  // [btnShowElm, btnHideElm].forEach(function(elm) {
  //   elm.addEventListener('click', () => {
  //     if(elm.id == 'btn-show') {
  //       chrome.storage.local.set({showQuicknav: true});
  //       statusTextElm.innerText = 'Quicknav Diaktifkan';
  //     } else if(elm.id == 'btn-hide') {
  //       chrome.storage.local.set({showQuicknav: false});
  //       statusTextElm.innerText = 'Quicknav Dimatikan'
  //     }
  //     notificationElm.classList.add('show');
  //   });
  // });
    
  // const { showQuicknav } =  await chrome.storage.local.get(['showQuicknav']);

  // const statusTextElm = document.querySelector('#status-text');
  // if (showQuicknav) { statusTextElm.innerText = 'Quicknav Diaktifkan' }
  // else { statusTextElm.innerText = 'Quicknav Dimatikan' }

  document.querySelector('#btn-show-ut').addEventListener('click', function (e) {
    if(this.classList.contains('open')) {
      this.classList.remove('open');
      this.classList.add('close');
      this.children[0].setAttribute('src', '../resources/icons/caret-up-fill.svg');
    } else {
      this.classList.remove('close');
      this.classList.add('open');
      this.children[0].setAttribute('src', '../resources/icons/caret-down-fill.svg');
    }
    // document.querySelector('.toggle-qn-container').classList.toggle('show');
    document.querySelector('.ut__body').classList.toggle('show');
  })
});

