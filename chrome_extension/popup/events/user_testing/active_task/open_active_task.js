import { 
    bodyUserTesting,
    bodyUserTestingActiveTask as body, 
    spinner, 
  } from "../../../elements.js";
import { getAccessToken, resetStackView, getCurrentTab } from "../../../utils.js";
import getHtml from "../../../templates/user_testing/active_task/active_task.js";
import divHintInfo from "../../../templates/user_testing/active_task/hint_information.js";
// import divSetupInfo from "../../../templates/user_testing/active_task/setup_information.js";
import divSelectFileInfo from "../../../templates/user_testing/active_task/select_file_info.js";

const eventType = 'UT/ACTIVE_TASK/OPEN_ACTIVE_TASK';
const eventCreator = (options) => {
  return new CustomEvent(eventType, options)
}

const eventHandler = async (e) => {
  console.log('event:', eventType);
  chrome.storage.local.set({lastPopupEvent: {type: eventType, detail: e.detail}});

  resetStackView();

  bodyUserTesting.classList.add('show');
  body.classList.add('show');
  body.innerHTML = '';
  body.append(spinner);

  chrome.storage.local.get(['activeTask'], async ({activeTask}) => {
    if(activeTask.itemId === null) {
      body.innerHTML = '<div class="p-3">Tidak ada tugas yang aktif, silahkan pilih tugas yang belum selesai dikerjakan.</div>';
      return;
    }

    const accessToken = await getAccessToken();
    let res = await fetch('http://localhost:8080/api/item/detail?item_id='+activeTask.itemId, {
      headers: { 'Authorization': 'Basic ' + btoa(`${accessToken}:password`) },
    });
    res = await res.json();

    // content html
    const content = document.createElement('div');
    content.className = 'p-3';
    content.innerHTML = getHtml(
      res.taskItem.code,
      res.taskItem.status,
      (activeTask.status == 'idle' || activeTask.status == 'end') 
        ? 'Tidak dijalankan' : 'Sedang dijalankan',
      res.taskItem.file_name,
    );

    // button setup
    /*
    const btnSetup = document.createElement('button');
    btnSetup.className = 'd-inline-block btn btn-sm btn-info me-3';
    btnSetup.innerText = 'Siapkan Tugas';
    btnSetup.addEventListener('click', async () => {

      // check item task interface
      if (activeTask.interface == 'GOOGLE_DRIVE') {
        // unregister content script
        await chrome.storage.local.set({showQuicknav: false});
      } 
      else if(activeTask.interface == 'QUICKNAV') {
        // trigger re-register content script
        // set task status idle
        await chrome.storage.local.set({showQuicknav: false});
        await chrome.storage.local.set({
          activeTask: {
            itemId: activeTask.itemId,
            status: 'idle',
            interface: 'QUICKNAV',
          },
          showQuicknav: true,
        });
      }

      // navigate to home url
      const tab = await getCurrentTab();
      chrome.scripting.executeScript({
        target: {tabId: tab.id},
        func: () => {
          window.location.href = 'https://drive.google.com/drive/my-drive';
        },
      });

    });
    */

    // button show hint
    const btnShowHint = document.createElement('button');
    btnShowHint.className = 'd-inline-block btn btn-sm btn-warning me-3';
    btnShowHint.innerText = 'Tampilkan Petunjuk';
    btnShowHint.addEventListener('click', () => {
      alert(`Petunjuk lokasi file :\n${res.taskItem.path_to_file}`);
    });

    // run task button
    const btnBegin = document.createElement('button');
    btnBegin.className = 'd-inline-block btn btn-sm btn-primary';
    btnBegin.innerText = 'Mulai Tugas';
    btnBegin.addEventListener('click', async () => {

      if(activeTask.interface == 'GOOGLE_DRIVE') {
        const currentTab = await getCurrentTab();
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
        window.close();
      } 

      else if(activeTask.interface == 'QUICKNAV') {
        // send to server, task is begin
        let res = await fetch(
          'http://localhost:8080/api/item/log-action'
            +'?action=BEGIN_TASK'
            +'&object=PREVIOUS'
            +'&time='+Math.floor(new Date().getTime()/1000.0)
            +'&task_item_id='+activeTask.itemId,
          { headers: { 'Authorization': 'Basic ' + btoa(`${accessToken}:password`) }}
        );
        res = await res.json();
        console.log('log action result', res);

        await chrome.storage.local.set({ 
          activeTask: {
            itemId: activeTask.itemId,
            status: 'running',
            interface: 'QUICKNAV',
          }
        });
        window.close();
      }

    });

    // end task button
    const btnEnd = document.createElement('button');
    btnEnd.className = 'd-inline-block btn btn-sm btn-success';
    btnEnd.innerText = 'Tugas Selesai';
    btnEnd.addEventListener('click', async () => {

      if(activeTask.interface == 'GOOGLE_DRIVE') {
        const currentTab = await getCurrentTab();
        let {taskLog} = await chrome.storage.local.get(['taskLog']);
        taskLog.push({
          action: 'END_TASK',
          object: currentTab.url,
          time: Math.floor(new Date().getTime()/1000.0),
        });
        // send log to background
        chrome.runtime.sendMessage({
          code: "FINAL_TASK_LOG",
          data: { logs: taskLog, taskItemId: activeTask.itemId },
        });
        // set task status idle
        await chrome.storage.local.set({
          activeTask: {
            itemId: activeTask.itemId,
            status: 'idle',
            interface: 'GOOGLE_DRIVE',
          },
          taskLog: [],
        });
      }

      else if(activeTask.interface == 'QUICKNAV') {
        // send to server, task is end
        let res = await fetch(
          'http://localhost:8080/api/item/log-action'
            +'?action=END_TASK'
            +'&object=PREVIOUS'
            +'&time='+Math.floor(new Date().getTime()/1000.0)
            +'&task_item_id='+activeTask.itemId,
          { headers: { 'Authorization': 'Basic ' + btoa(`${accessToken}:password`) }}
        );
        res = await res.json();
        console.log('log action result', res);
        
        
        // set task status end
        await chrome.storage.local.set({
          activeTask: {
            itemId: activeTask.itemId,
            status: 'end',
            interface: 'QUICKNAV',
          },
          taskLog: [],
          showQuicknav: false,
        });
        // trigger re-register content script
        await chrome.storage.local.set({showQuicknav: true});
      }
      
      // navigate to home url / refresh tab
      // to take effect of new content script
      const tab = await getCurrentTab();
      chrome.scripting.executeScript({
        target: {tabId: tab.id},
        func: () => {
          window.location.href = 'https://drive.google.com/drive/my-drive';
        },
      });

      window.close();
    });

    // cancel task button
    const btnCancel = document.createElement('button');
    btnCancel.className = 'd-inline-block btn btn-sm btn-secondary me-3';
    btnCancel.innerText = 'Batalkan Tugas';
    btnCancel.addEventListener('click', async () => {
      
      if(activeTask.interface == 'GOOGLE_DRIVE') {
        // set task status idle
        await chrome.storage.local.set({
          activeTask: {
            itemId: activeTask.itemId,
            status: 'idle',
            interface: activeTask.interface,
          },
          taskLog: [],
        });
      }

      else if(activeTask.interface == 'QUICKNAV') {
        // send to server, task is cancel
        let res = await fetch(
          'http://localhost:8080/api/item/log-action'
            +'?action=CANCEL_TASK'
            +'&object=PREVIOUS'
            +'&time='+Math.floor(new Date().getTime()/1000.0)
            +'&task_item_id='+activeTask.itemId,
          { headers: { 'Authorization': 'Basic ' + btoa(`${accessToken}:password`) }}
        );
        res = await res.json();
        console.log('log action result', res);

        // set task status end
        await chrome.storage.local.set({
          activeTask: {
            itemId: activeTask.itemId,
            status: 'idle',
            interface: activeTask.interface,
          },
          taskLog: [],
          showQuicknav: false,
        });
        // trigger re-register content script
        await chrome.storage.local.set({showQuicknav: true});
      }
      
      // navigate to home url / refresh tab
      // to take effect of new content script
      const tab = await getCurrentTab();
      chrome.scripting.executeScript({
        target: {tabId: tab.id},
        func: () => {
          window.location.href = 'https://drive.google.com/drive/my-drive';
        },
      });
      
      window.close();
    });

    if (activeTask.status == 'idle' || activeTask.status == 'end') {
      if(res.taskItem.hint_visible === 1) {
        content.append(divHintInfo);
        content.append(btnShowHint);
      }
      content.append(btnBegin);
    } else if (activeTask.status == 'running') {
      content.append(divSelectFileInfo);
      content.append(btnCancel);
      content.append(btnEnd);
    }

    body.innerHTML = '';
    body.append(content);  
  })

}

export {eventType, eventCreator, eventHandler};