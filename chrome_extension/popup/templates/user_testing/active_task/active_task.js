const getHtml = (code, status, description) => {
  return `
    <span id="task-item-code" class="d-block fs-6 fw-semibold mb-3">${code}</span>
    <span class="d-block text-black-50" style="font-size: 12px;">Status</span>
    <span id="task-item-status" class="d-block mb-3">${status}</span>
  
    <span class="d-block text-black-50" style="font-size: 12px;">Deskripsi</span>
    <span id="task-item-description" class="d-block mb-3">Pergi ke file dengan deskripsi: ${description}</span>
  `;
}

export default getHtml;