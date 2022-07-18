main();

async function main() {
  const QN_PARENT_ELM_SEL = 'div[class=g3Fmkb]';
  const qnParentElement = document.querySelector(QN_PARENT_ELM_SEL);

  // create quicknav element
  const qnElm = document.createElement('div');
  qnElm.id = 'qn-root';

  // create iframe element
  const iframe = document.createElement('iframe');
  
  // add newly created element to dom
  qnElm.append(iframe);
  qnParentElement.prepend(qnElm);

  // get html
  const {gToken} = await chrome.storage.local.get(['gToken']);
  const html = await fetch('http://localhost:8081/quicknav/navigation?folder_id=root&keyword=networking', {
    headers: {
      'Authorization': 'Bearer ' + gToken.value
    }
  }).then(res => res.text());

  // add content to iframe
  const qnWindow = iframe.contentWindow;
  qnWindow.document.open();
  qnWindow.document.write(html);
  qnWindow.document.close();

}