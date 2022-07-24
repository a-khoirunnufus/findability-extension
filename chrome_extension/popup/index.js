import { otlType, otlEvent, otlHandler } from './events/open_task_list.js';
import { otliType, otliHandler } from './events/open_task_list_items.js';


document.addEventListener('DOMContentLoaded', function() {

  document.addEventListener(otlType, otlHandler);   // OPEN TASK LIST
  document.addEventListener(otliType, otliHandler); // OPEN TASK LIST ITEMS
  
  document.dispatchEvent(otlEvent);

});