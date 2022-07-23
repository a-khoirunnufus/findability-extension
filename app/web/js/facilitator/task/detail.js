window.addEventListener('DOMContentLoaded', function() {

  let selectedFile = {};

  document.querySelectorAll('input[name="file_id"]').forEach((elm) => {
    elm.addEventListener('change', function() {
      if(this.checked) {
        selectedFile[this.value] = true;
      } else {
        delete selectedFile[this.value];
      }
      document.querySelector('#selected-file-count').innerText = Object.keys(selectedFile).length;
    });
  });

  const formSelectFiles1 = document.querySelector('#form-select-files-1');
  document.querySelector('#submit-selected-files-1').addEventListener('click', () => {
    let selectedFileText = Object.keys(selectedFile);
    selectedFileText = selectedFileText.join(',');
    document.querySelector('#form-select-files-1 input[name="selected_files"]').value = selectedFileText;
    formSelectFiles1.submit();
  });

});