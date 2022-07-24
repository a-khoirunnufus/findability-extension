import { 
    bodyUserTesting,
    bodyUserTestingCurrent as body, 
    spinner, 
  } from "../elements.js";
import { getAccessToken, resetStackView } from "../utils.js";

const otcType = 'OPEN_TASK_CURRENT';
const otcEventCreator = (options) => {
  return new CustomEvent(otcType, options)
}

const otcHandler = async (e) => {
  console.log('event: open task current');
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

  console.log(res);
}

export {otcType, otcEventCreator, otcHandler};