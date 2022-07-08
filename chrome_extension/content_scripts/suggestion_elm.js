function hideSuggestionElm() {
  const SUG_ELM_SEL = 'div[jsname=bJy2od]';
  const sugElm = document.querySelector(SUG_ELM_SEL);
  sugElm.style.display = "none";
  
  chrome.runtime.sendMessage({
    type: "BACKGROUND_LOG",
    time: new Date().toISOString(), 
    message: "'disarankan' element was hidden"}
  );
}

function showSuggestionElm() {
  const SUG_ELM_SEL = 'div[jsname=bJy2od]';
  const sugElm = document.querySelector(SUG_ELM_SEL);
  sugElm.style.display = "block";
  
  chrome.runtime.sendMessage({
    type: "BACKGROUND_LOG",
    time: new Date().toISOString(), 
    message: "'disarankan' element was shown"}
  );
}

export {hideSuggestionElm, showSuggestionElm}