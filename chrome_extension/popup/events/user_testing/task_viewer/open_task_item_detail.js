import { 
    bodyUserTesting,
    bodyUserTestingTaskViewer as body, 
    spinner, 
  } from "../../../elements.js";
import { getAccessToken, resetStackView } from "../../../utils.js";
import { eventCreator as openTaskItemListEventCreator } from './open_task_item_list.js';
import { eventCreator as openActiveTaskEventCreator } from '../active_task/open_active_task.js';
import getHtml from "../../../templates/user_testing/task_viewer/task_item_detail.js";

const eventType = 'UT/TASK_VIEWER/OPEN_TASK_ITEM_DETAIL';
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

  const accessToken = await getAccessToken();
  let res = await fetch('http://localhost:8080/api/item/detail?item_id='+e.detail.itemId, {
    headers: { 'Authorization': 'Basic ' + btoa(`${accessToken}:password`) }
  });
  res = await res.json();

  // back button
  const btnBack = document.createElement('a');
  btnBack.setAttribute('href', '#');
  btnBack.className = 'mb-3 d-inline-block';
  btnBack.innerText = 'Kembali';
  btnBack.addEventListener('click', () => {
    document.dispatchEvent(openTaskItemListEventCreator({
      detail: {
        taskId: e.detail.taskId,
      }
    }));
  })

  // content html
  const content = document.createElement('div');
  content.className = 'p-3';
  content.innerHTML = getHtml(
    res.taskItem.code,
    (res.taskItem.is_complete == "0") ? 'Belum selesai' : 'Selesai',
    res.taskItem.description,
  );

  // run task button
  const btnRun = document.createElement('button');
  btnRun.className = 'd-inline-block btn btn-sm btn-primary';
  btnRun.innerText = 'Jalankan Tugas';
  btnRun.addEventListener('click', () => {
    console.log('run task item');
    // set active task
    chrome.storage.local.set({
      activeTask: {
        itemId: e.detail.itemId,
        status: 'idle',
      }
    }, () => { 
      // open active task stack
      document.dispatchEvent(openActiveTaskEventCreator({}));
    });
  })

  content.prepend(btnBack);
  content.append(btnRun);
  body.innerHTML = '';
  body.append(content);
}

export {eventType, eventCreator, eventHandler};