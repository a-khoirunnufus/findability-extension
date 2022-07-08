function addQuicknavElm() {
  const qnElm = document.createElement('div');
  qnElm.id = 'qn-root';
  qnElm.style.gridArea = 'qn';
  qnElm.style.border = '1px solid gainsboro';
  qnElm.style.padding = '1rem';
  qnElm.style.margin = '.5rem .5rem .5rem 0';
  qnElm.innerHTML = 
    '<h3>Navigasi Cepat</h3>'+
    '<div id="shortcut-list-wrapper"></div>';

  const QN_PARENT_ELM_SEL = 'div[class=g3Fmkb]';
  const qnParentElement = document.querySelector(QN_PARENT_ELM_SEL);
  qnParentElement.style.gridTemplateAreas = '"qn qn""tlbr tlbr""view info"';
  qnParentElement.style.gridTemplateRows = 'auto auto 1fr';
  qnParentElement.prepend(qnElm);

  chrome.runtime.sendMessage({
    type: "BACKGROUND_LOG",
    time: new Date().toISOString(), 
    message: "'QuickNav' element was added"}
  );
}

function hideQuicknavElm() {
  const QN_ELM_SEL = 'div[id="qn-root"]';
  const qnElm = document.querySelector(QN_ELM_SEL);
  qnElm.style.display = "none";
  
  chrome.runtime.sendMessage({
    type: "BACKGROUND_LOG",
    time: new Date().toISOString(), 
    message: "'QuickNav' element was hidden"}
  );
}

function showQuicknavElm() {
  const QN_ELM_SEL = 'div[id="qn-root"]';
  const qnElm = document.querySelector(QN_ELM_SEL);
  qnElm.style.display = "block";
  
  chrome.runtime.sendMessage({
    type: "BACKGROUND_LOG",
    time: new Date().toISOString(), 
    message: "'QuickNav' element was shown"}
  );
}

export {addQuicknavElm, hideQuicknavElm, showQuicknavElm}