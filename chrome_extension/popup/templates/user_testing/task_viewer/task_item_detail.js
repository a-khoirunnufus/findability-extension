const getHtml = (code, status, description) => {
  return `
    <div class="p-3">
      <span id="task-item-code" class="d-block fs-6 fw-semibold mb-3">${code}</span>
      <span class="d-block text-black-50" style="font-size: 12px;">Status</span>
      <span id="task-item-status" class="d-block mb-3">${status}</span>
    
      <span class="d-block text-black-50" style="font-size: 12px;">Deskripsi</span>
      <span id="task-item-description" class="d-block mb-3">Pergi ke file dengan deskripsi: ${description}</span>

      <button id="btn-start-task-item" class="d-inline-block btn btn-sm btn-primary">Kerjakan Tugas</button>
    </div>
  `;
}

export default getHtml;