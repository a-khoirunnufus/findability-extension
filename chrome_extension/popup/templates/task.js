const html = `
  <div class="card">
    <div class="card-body p-3">
      <span id="task-item-code" class="d-block fs-6 fw-semibold mb-3">QH-01</span>
      <span class="d-block text-black-50" style="font-size: 12px;">Status</span>
      <span id="task-item-status" class="d-block mb-3">Tugas belum dimulai</span>
    
      <span class="d-block text-black-50" style="font-size: 12px;">Deskripsi</span>
      <span id="task-item-description" class="d-block mb-3">Laporan tugas desain interaksi minggu ke 6</span>

      <div class="alert alert-warning d-flex align-items-center mb-3 py-2 px-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span class="ms-3" style="font-size: 12px;">
          Petujuk hanya dapat ditampilkan satu kali, pastikan anda mengingatnya.
        </span>
      </div>

      <button id="btn-open-task-item-hint" class="d-inline-block btn btn-sm btn-warning">Lihat Petujuk</button>
      <button id="btn-start-task-item" class="d-inline-block btn btn-sm btn-primary">Mulai Tugas</button>
    </div>
  </div>
`;

export default html;