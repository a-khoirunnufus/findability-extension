import { otlType, otlEvent, otlHandler } from './events/open_task_list.js';
import { otliType, otliHandler } from './events/open_task_list_items.js';
import { otcType, otcHandler } from './events/open_task_current.js';

document.addEventListener('DOMContentLoaded', function() {

  document.addEventListener(otlType, otlHandler);   // OPEN TASK LIST
  document.addEventListener(otliType, otliHandler); // OPEN TASK LIST ITEMS
  document.addEventListener(otcType, otcHandler);   // OPEN TASK CURRENT
  
  document.dispatchEvent(otlEvent);

});