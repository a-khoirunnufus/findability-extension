const getHtml = (code, itemStatus, appStatus, description) => {
  let html = `
    <h6 id="task-item-code" class="d-block fs-6 fw-semibold mb-3">
      ${code}&nbsp;&nbsp;`;

  if(itemStatus == 'NOT_COMPLETE') {
    html += '<span class="badge bg-secondary">belum selesai</span>'; 
  } else if(itemStatus == 'PENDING') {
    html += '<span class="badge bg-warning">pending</span>';
  } else if(itemStatus == 'COMPLETED') {
    html += '<span class="badge bg-success">selesai</span>';
  }

  html += `
    </h6>

    <span class="d-block text-black-50" style="font-size: 12px;">Status</span>
    <span id="task-item-status" class="d-block mb-3">${appStatus}</span>
  
    <span class="d-block text-black-50" style="font-size: 12px;">Deskripsi</span>
    <span id="task-item-description" class="d-block mb-3">Pergi ke file dengan deskripsi: ${description}</span>
  `;

  return html;
}

export default getHtml;