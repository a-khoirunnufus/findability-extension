// add quicknav element on dom
const qnElm = document.createElement('div');
qnElm.id = 'qn-root';
qnElm.style.gridArea = 'qn';
qnElm.style.border = '1px solid gainsboro';
qnElm.style.padding = '1rem';
qnElm.style.margin = '.5rem .5rem .5rem 0';
qnElm.innerHTML = 
  '<h3>QuickNav</h3>'+
  '<div id="adaptive-view-wrapper"></div>';
qnElm.style.display = 'none';

const QN_PARENT_ELM_SEL = 'div[class=g3Fmkb]';
const qnParentElement = document.querySelector(QN_PARENT_ELM_SEL);
qnParentElement.style.gridTemplateAreas = '"qn qn""tlbr tlbr""view info"';
qnParentElement.style.gridTemplateRows = 'auto auto 1fr';
qnParentElement.prepend(qnElm);

renderAdaptiveView();

async function renderAdaptiveView() {
  const aElm = document.querySelector('#qn-root #adaptive-view-wrapper');
  aElm.innerHTML = 'Loading data...';

  const {gToken} = await chrome.storage.local.get(['gToken']);

  const data = await fetch('http://localhost:8081/file/index', {
    headers: {
      'Authorization': 'Bearer ' + gToken.value
    }
  }).then(res => res.json())

  aElm.innerHTML = data.data;
}
