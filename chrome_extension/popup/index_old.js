import taskItemTemplate from './task/template.js';

window.addEventListener('DOMContentLoaded', async function(e) {
  
  // render task list start
  // document.querySelector('#list-content-wrapper').innerHTML;
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

// loading spinner html
const spinner = `
  <div class="spinner-border" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
`;

// get task list
renderTask();

async function renderTask() {
  const listWrapper = document.querySelector('#list-content-wrapper');
  listWrapper.innerHTML = spinner;

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
    listItem.innerText = item.code + ' - ' + item.name;

    const btn = document.createElement('button');
    btn.innerText = 'Buka';
    btn.className = 'btn btn-primary btn-sm';
    btn.addEventListener('click', () => {
      renderTaskItems(item.id);
    });

    listItem.append(btn);
    list.append(listItem);
  }
  
  listWrapper.innerHTML = '';
  listWrapper.append(list);
}

async function renderTaskItems(taskId) {
  const listWrapper = document.querySelector('#list-content-wrapper');
  listWrapper.innerHTML = spinner;

  const accessToken = await getAccessToken();
  let res = await fetch('http://localhost:8080/api/item/index?task_id='+taskId, {
    headers: {
      'Authorization': 'Basic ' + btoa(`${accessToken}:password`),
    }
  });
  res = await res.json();

  // construct list html
  const list = document.createElement('ul');
  list.classList.add('list-group');

  for (const item of res.taskItems) {
    const listItem = document.createElement('li');
    listItem.className = 'list-group-item d-flex flex-row justify-content-between align-items-center';
    listItem.classList.add('list-group-item');
    
    const itemText = document.createElement('span');
    itemText.innerHTML = `${item.code} &nbsp;&nbsp;&nbsp; ${
      item.is_complete == "0" ? '<span class="badge text-bg-secondary">belum selesai</span>' : '<span class="badge text-bg-success">selesai</span>'
    }`;
    
    const btn = document.createElement('button');
    btn.innerText = 'Pilih';
    btn.className = 'btn btn-primary btn-sm';
    btn.addEventListener('click', () => {
      renderTaskItem(item.id);
    });
    
    listItem.append(itemText);
    listItem.append(btn);
    list.append(listItem);
  }

  
  listWrapper.innerHTML = '';
  listWrapper.append(list);
} 

// case selecting item
async function renderTaskItem(itemId) {
  const contentWrapper = document.querySelector('#task-content-wrapper');
  contentWrapper.innerHTML = spinner;

  const accessToken = await getAccessToken();
  let res = await fetch('http://localhost:8080/api/item/detail?item_id='+itemId, {
    headers: {
      'Authorization': 'Basic ' + btoa(`${accessToken}:password`),
    }
  });
  res = await res.json();

  // construct list html
  console.log(res);
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
