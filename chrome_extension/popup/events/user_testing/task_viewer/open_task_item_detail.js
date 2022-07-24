import { 
    bodyUserTesting,
    bodyUserTestingTaskViewer as body, 
    spinner, 
  } from "../../../elements.js";
import { getAccessToken, resetStackView } from "../../../utils.js";
import { eventCreator as openTaskItemListEventCreator } from './open_task_item_list.js';
import getHtml from "../../../templates/user_testing/task_viewer/task_item_detail.js";

const eventType = 'UT/TASK_VIEWER/OPEN_TASK_ITEM_DETAIL';
const eventCreator = (options) => {
  return new CustomEvent(eventType, options)
}

const eventHandler = async (e) => {
  console.log(e.detail);
  console.log('event:', eventType);
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
  btnBack.className = 'py-2 ps-3 mb-3 d-inline-block';
  btnBack.innerText = 'Kembali';
  btnBack.addEventListener('click', () => {
    document.dispatchEvent(openTaskItemListEventCreator({
      detail: {
        taskId: e.detail.taskId,
      }
    }));
  })

  const html = getHtml(
    res.taskItem.code,
    (res.taskItem.is_complete == "0") ? 'Belum selesai' : 'Selesai',
    res.taskItem.description,
  );

  body.innerHTML = html;
  body.prepend(btnBack);
}

export {eventType, eventCreator, eventHandler};