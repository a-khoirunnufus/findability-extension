import { 
    bodyUserTesting,
    bodyUserTestingList as body, 
    spinner, 
  } from "../elements.js";
import { getAccessToken, resetStackView } from "../utils.js";
import { otliEventCreator } from "./open_task_list_items.js";

const otlType = 'OPEN_TASK_LIST';
const otlEvent = new Event(otlType);

const otlHandler = async (e) => {
  console.log('event: open task list');
  resetStackView();

  bodyUserTesting.classList.add('show');
  body.classList.add('show');
  body.innerHTML = '';
  body.append(spinner);

  const accessToken = await getAccessToken();
  let res = await fetch('http://localhost:8080/api/task/index', {
    headers: { 'Authorization': 'Basic ' + btoa(`${accessToken}:password`) }
  });
  res = await res.json();

  // construct list html
  const list = document.createElement('ul');
  list.className = 'list-group list-group-flush';

  for (const task of res.taskList) {
    const listItem = document.createElement('li');
    listItem.className = 'list-group-item d-flex flex-row justify-content-between align-items-center';
    listItem.classList.add('list-group-item');
    listItem.innerText = task.code + ' - ' + task.name;

    const btn = document.createElement('button');
    btn.innerText = 'Buka';
    btn.className = 'btn btn-primary btn-sm';
    btn.addEventListener('click', () => {
      document.dispatchEvent(otliEventCreator({
        detail: {
          taskId: task.id
        }
      }));
    });

    listItem.append(btn);
    list.append(listItem);
  }

  body.innerHTML = '';
  body.append(list);
}

export {otlType, otlEvent, otlHandler};