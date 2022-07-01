/**
 * PARAMS
 * todo:
 * - make different file for constant or param
 */
const GD_SUG_SECTION_SEL = 'div[jsname=bJy2od]';

// hide gd suggested element
const sugElm = document.querySelector(GD_SUG_SECTION_SEL);
sugElm.style.display = "none";
console.log('hiding gd suggested element');

// setup starter quick navigation element
const qnElm = document.createElement('div');
qnElm.id = 'qn-root';
qnElm.style.gridArea = 'qn';
qnElm.style.border = '1px solid gainsboro';
qnElm.style.padding = '1rem';
qnElm.style.margin = '.5rem .5rem .5rem 0';
qnElm.innerHTML = 
  '<h3>Navigasi Cepat</h3>'+
  '<div id="shortcut-list-wrapper"></div>';

// inject QN element
const QN_PARENT_ELM_SEL = 'div[class=g3Fmkb]';
const qnParentElement = document.querySelector(QN_PARENT_ELM_SEL);
qnParentElement.style.gridTemplateAreas = '"qn qn""tlbr tlbr""view info"';
qnParentElement.style.gridTemplateRows = 'auto auto 1fr';
qnParentElement.prepend(qnElm);

// loading view for client
const shortcutWrapper = document.getElementById('shortcut-list-wrapper');

function loadShortcutList(folder_id) {
  shortcutWrapper.innerHTML = 'Sedang memuat pintasan...';
  fetch('http://localhost:8080/shortcut/'+folder_id)
    .then(response => response.json())
    .then(data => {
      // console.log('response data', data);
      shortcutWrapper.innerHTML = '<pre>'+JSON.stringify(data, null, 2)+'</pre>';
    });
}

// first load shortcuts
// todo: get current url, pass it to param below
loadShortcutList(0);

/**
 * handle user navigating
 * - detect user navigating by url
 * - if user navigating to root folderListElm(without refreshing page), hide suggested element again
 */

chrome.runtime.onMessage.addListener(
  function(request, sender, sendResponse) {
    if (request.status === 'URL_CHANGED') {
      console.log('url changed');
      sendResponse('CONTENT: message received');
  
      const url_arr = request.data.new_url.split('/');
      const folder_id = url_arr[url_arr.length - 1];
      console.log(folder_id);
      loadShortcutList(folder_id);
    }
  }
);