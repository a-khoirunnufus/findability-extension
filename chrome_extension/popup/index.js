window.addEventListener('DOMContentLoaded', async function(e) {
  
  // render task list start
  const spinner = `
    <div class="spinner-border" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  `;
  document.querySelector('#task-list-wrapper').innerHTML = spinner;
  // render task list end

  const notificationElm = document.querySelector('.notification');
  const btnShowElm = document.querySelector('#btn-show');
  const btnHideElm = document.querySelector('#btn-hide');
    
  [btnShowElm, btnHideElm].forEach(function(elm) {
    elm.addEventListener('click', () => {
      if(elm.id == 'btn-show') {
        chrome.storage.local.set({showQuicknav: true});
        statusTextElm.innerText = 'Quicknav diaktifkan';
      } else if(elm.id == 'btn-hide') {
        chrome.storage.local.set({showQuicknav: false});
        statusTextElm.innerText = 'Quicknav dimatikan'
      }
      notificationElm.classList.add('show');
    });
  });
    
  const { 
      showQuicknav,
      popup_qnOpen,
      popup_utOpen,
      popup_utCurrentOpen,
      popup_utListOpen 
    } = await chrome.storage.local.get([
      'showQuicknav',
      'popup_qnOpen',
      'popup_utOpen',
      'popup_utCurrentOpen',
      'popup_utListOpen',
    ]);

  const statusTextElm = document.querySelector('#status-text');
  const qnBodyElm = document.querySelector('#quicknav').nextElementSibling;
  const utBodyElm = document.querySelector('#user-testing').nextElementSibling;
  const utCurrentBodyElm = utBodyElm.firstElementChild;
  const utListBodyElm = utBodyElm.lastElementChild;

  if (showQuicknav) { statusTextElm.innerText = 'Quicknav diaktifkan' }
  else { statusTextElm.innerText = 'Quicknav dimatikan' }

  if (popup_qnOpen) qnBodyElm.classList.add('show');
  if (popup_utOpen) utBodyElm.classList.add('show');
  if (popup_utCurrentOpen) utCurrentBodyElm.classList.add('show');
  if (popup_utListOpen) utListBodyElm.classList.add('show');

  // document.querySelector('#btn-show-ut').addEventListener('click', function (e) {
  //   if(this.classList.contains('open')) {
  //     this.classList.remove('open');
  //     this.classList.add('close');
  //     this.children[0].setAttribute('src', '../resources/icons/caret-up-fill.svg');
  //   } else {
  //     this.classList.remove('close');
  //     this.classList.add('open');
  //     this.children[0].setAttribute('src', '../resources/icons/caret-down-fill.svg');
  //   }
  //   // document.querySelector('.toggle-qn-container').classList.toggle('show');
  //   document.querySelector('.ut__body').classList.toggle('show');
  // })

  const stackElms = document.querySelectorAll('.stack__header-toggler');
  stackElms.forEach((elm) => {
    elm.addEventListener('click', function() {
      const id = this.id;
      const children = this.parentElement.children;
      for(let child of children) {
        if(child.tagName != 'SCRIPT') {
          if(child.classList.contains('stack__header-toggler') && child.id == id) {
            child.nextElementSibling.classList.add('show');
            console.log('show body');
          } else {
            child.nextElementSibling?.classList.remove('show');
          }
        }
      }
    });
  });
});

// get task list
renderTaskList();

async function renderTaskList() {
  const accessToken = await getAccessToken();
  let res = await fetch('http://localhost:8080/api/task/index', {
    headers: {
      'Authorization': 'Basic ' + btoa(`${accessToken}:password`),
    }
  });
  res = await res.json();

  // construct list html
  const list = document.createElement('ul');
  list.classList.add('list-group');

  for (const item of res.task) {
    const listItem = document.createElement('li');
    listItem.className = 'list-group-item d-flex flex-row justify-content-between align-items-center';
    listItem.classList.add('list-group-item');
    listItem.innerText = item.code + ' ' + item.name;

    const btn = document.createElement('button');
    btn.innerText = 'Buka';
    btn.className = 'btn btn-primary btn-sm';

    listItem.append(btn);
    list.append(listItem);
  }
  
  const listWrapper = document.querySelector('#task-list-wrapper');
  listWrapper.innerHTML = '';
  listWrapper.append(list);
}

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
