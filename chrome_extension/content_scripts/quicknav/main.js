main();

function main() {
  const QN_PARENT_ELM_SEL = 'div[class=g3Fmkb]';
  const qnParentElement = document.querySelector(QN_PARENT_ELM_SEL);

  // create quicknav element
  const qnElm = document.createElement('div');
  qnElm.id = 'qn-root';

  // create iframe element
  const iframe = document.createElement('iframe');
  iframe.id = 'quicknav';
  iframe.src = "http://localhost:8080/quicknav/navigation/index?folder_id=root&keyword=&sort_key=name&sort_dir=4";

  // add newly created element to dom
  qnElm.append(iframe);
  qnParentElement.prepend(qnElm);

  iframe.contentWindow.document.addEventListener('readystatechange', (event) => {
    console.log('readystate changed:', iframe.contentWindow.document.readyState);
  });

  // get html
  // SORT_ASC = 4
  // SORT_DESC = 3
  // const {gToken} = await chrome.storage.local.get(['gToken']);
  // const html = await fetch('http://localhost:8081/quicknav/index?folder_id=root&keyword=networking&sort_key=name&sort_dir=4', {
  //   headers: {
  //     'Authorization': 'Bearer ' + gToken.value
  //   }
  // }).then(res => res.text());

  // add content to iframe
  // const qnWindow = iframe.contentWindow;
  // qnWindow.document.open();
  // qnWindow.document.write(html);
  // qnWindow.document.close();

}