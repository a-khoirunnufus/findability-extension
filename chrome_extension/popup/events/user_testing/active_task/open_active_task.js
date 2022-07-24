import { 
    bodyUserTesting,
    bodyUserTestingActiveTask as body, 
    spinner, 
  } from "../../../elements.js";
import { resetStackView } from "../../../utils.js";

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
  body.innerHTML = 'task yang sedang aktif';
  // body.append(spinner);

}

export {eventType, eventCreator, eventHandler};