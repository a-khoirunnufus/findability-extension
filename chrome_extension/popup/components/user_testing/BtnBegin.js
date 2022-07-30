import { parseURL } from "../../utils.js";

function getBtnBegin(currentTab, activeTask) {
  return new Promise(async (resolve, reject) => {
    const btnBegin = document.createElement('button');
    btnBegin.className = 'd-inline-block btn btn-sm btn-primary';
  
    const envReady = await checkEnvReady(activeTask.interface, currentTab);
    
    if(envReady.status) {
      btnBegin.innerText = 'Mulai Tugas';
      btnBegin.classList.add('btn-primary');
    } else {
      btnBegin.innerText = 'Siapkan Tugas';
      btnBegin.classList.add('btn-warning');
    }
  
    btnBegin.addEventListener('click', async () => {
      if(envReady.interface == 'GOOGLE_DRIVE') {
        if(!envReady.status) {
          // env not ready
          await chrome.scripting.executeScript({
            target: {tabId: currentTab.id},
            func: () => {
              window.location.href = 'https://drive.google.com/drive/my-drive';
              alert('Sedang menyiapkan konfigurasi, silahkan mulai tugas setelah halaman selesai direfresh.');
            },
          });
          return window.close();
        }
  
        // env ready
        // set task status running
        await chrome.storage.local.set({ 
          activeTask: {
            itemId: activeTask.itemId,
            status: 'running',
            interface: 'GOOGLE_DRIVE',
          },
          taskLog: [{
            action: 'BEGIN_TASK',
            object: currentTab.url,
            time: Math.floor(new Date().getTime()/1000.0),
          }],
        });
  
        return window.close();
      }
      else if(envReady.interface == 'QUICKNAV') {
        if(!envReady.status) {
          // env not ready
          await chrome.storage.local.set({showQuicknav: true});  
          // refresh page
          await chrome.scripting.executeScript({
            target: {tabId: currentTab.id},
            func: () => {
              window.location.href = 'https://drive.google.com/drive/my-drive';
              alert('Sedang menyiapkan konfigurasi, silahkan mulai tugas setelah halaman selesai direfresh.');
            },
          });
          return window.close();
        }
  
        // env ready
        await chrome.scripting.executeScript({
          target: {tabId: currentTab.id},
          func: beginTaskQuicknav,
          args: [activeTask],
        });
    
        await chrome.storage.local.set({ 
          activeTask: {
            itemId: activeTask.itemId,
            status: 'running',
            interface: 'QUICKNAV',
          }
        });
        
        return window.close();
      }
    });

    resolve(btnBegin);
  })
}

async function checkEnvReady(viewInterface, currentTab) {
  let ready = true;

  if(viewInterface == 'GOOGLE_DRIVE') {
    // checking environment ready
    const {showQuicknav} = await chrome.storage.local.get(['showQuicknav']);
    if(showQuicknav) {
      // not ready
      ready = false;
      await chrome.storage.local.set({showQuicknav: false});
    }
    const path = parseURL(currentTab.url).path;
    const pathArr = path.split('/');
    if(pathArr.pop() != 'my-drive') {
      // not ready
      ready = false;
    }
  }
  else if(viewInterface == 'QUICKNAV') {
    // checking environment ready
    const {showQuicknav} = await chrome.storage.local.get(['showQuicknav']);
    if(showQuicknav === false) {
      ready = false;
    }
  }

  return {
    status: ready,
    interface: viewInterface,
  };
}

function beginTaskQuicknav(activeTask) {
  const iframe = document.querySelector('iframe[id="quicknav"]');
  iframe.setAttribute('src', 'http://localhost:8080/quicknav/navigation/index?folder_id=root&sort_key=name&sort_dir=4&source=init&log=BEGIN_TASK-'+activeTask.itemId);
}

export default getBtnBegin;