import { eventType as utotlType, event as utotlEvent, eventHandler as utotlHandler } 
    from './events/user_testing/task_viewer/open_task_list.js';
import { eventType as utotilType, eventHandler as utotilHandler } 
    from './events/user_testing/task_viewer/open_task_item_list.js';
import { eventType as utotidType, eventHandler as utotidHandler } 
    from './events/user_testing/task_viewer/open_task_item_detail.js';

document.addEventListener('DOMContentLoaded', function() {

  document.addEventListener(utotlType, utotlHandler);   // OPEN TASK LIST
  document.addEventListener(utotilType, utotilHandler); // OPEN TASK ITEM LIST
  document.addEventListener(utotidType, utotidHandler); // OPEN TASK ITEM DETAIL
  
  document.dispatchEvent(utotlEvent);

});