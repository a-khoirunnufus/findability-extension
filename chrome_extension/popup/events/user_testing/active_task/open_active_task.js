import { 
    bodyUserTesting,
    bodyUserTestingActiveTask as body, 
    spinner, 
  } from "../../../elements.js";
import { getAccessToken, resetStackView } from "../../../utils.js";
import getHtml from "../../../templates/user_testing/active_task/active_task.js";

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
      (activeTask.status == "idle") ? 'Tidak dijalankan' : 'Sedang dijalankan',
      res.taskItem.description,
    );

    // run task button
    const btnBegin = document.createElement('button');
    btnBegin.className = 'd-inline-block btn btn-sm btn-success';
    btnBegin.innerText = 'Mulai Tugas';
    btnBegin.addEventListener('click', () => {
      if (window.confirm(`Petunjuk lokasi file: ${res.taskItem.path_to_file}`)) {
        console.log('task item begin');
        // update storage, item status
      }
    });

    content.append(btnBegin);
    body.innerHTML = '';
    body.append(content);  
  })

}

export {eventType, eventCreator, eventHandler};