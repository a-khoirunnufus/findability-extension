import { 
    bodyUserTesting,
    bodyUserTestingList as body, 
    spinner, 
  } from "../elements.js";
import { getAccessToken, resetStackView } from "../utils.js";
import { otlEvent } from "./open_task_list.js";
import { otcEventCreator } from "./open_task_current.js";

const otliType = 'OPEN_TASK_LIST_ITEMS';
const otliEventCreator = (options) => {
  return new CustomEvent(otliType, options)
}

const otliHandler = async (e) => {
  console.log('event: open task list items')
  resetStackView();

  bodyUserTesting.classList.add('show');
  body.classList.add('show');
  body.innerHTML = '';
  body.append(spinner);

  const accessToken = await getAccessToken();
  let res = await fetch('http://localhost:8080/api/item/index?task_id='+e.detail.taskId, {
    headers: { 'Authorization': 'Basic ' + btoa(`${accessToken}:password`) }
  });
  res = await res.json();

  // back button
  const btnBack = document.createElement('a');
  btnBack.setAttribute('href', '#');
  btnBack.className = 'py-2 ps-3 mb-3 d-inline-block';
  btnBack.innerText = 'Kembali';
  btnBack.addEventListener('click', () => {
    document.dispatchEvent(otlEvent);
  })

  // construct list html
  const list = document.createElement('ul');
  list.className = 'list-group list-group-flush';

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
      document.dispatchEvent(otcEventCreator({
        detail: {
          itemId: item.id
        }
      }))
    });

    listItem.append(itemText);
    listItem.append(btn);
    list.append(listItem);
  }

  body.innerHTML = '';
  body.append(btnBack);
  body.append(list);
}

export {otliType, otliEventCreator, otliHandler};