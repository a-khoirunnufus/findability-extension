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


  // button submit final items click
  document.querySelector('#btn-submit-final-targets').addEventListener('click', () => {
    let finalTargets = [];

    // get all file frequency values
    const freqInputElmns = document.querySelectorAll('#table-mapping-file input[name="file_freq"]'); 
    freqInputElmns.forEach((elm) => {
      const fileId = elm.getAttribute('data-file-id');
      const frequency = elm.value;
      finalTargets.push(`${fileId}@${frequency}`);
    });
    
    // convert to string format to send in form
    const finalTargetsString = finalTargets.join(',');
    document.querySelector('#form-set-final-targets input[name="final_targets"]').value = finalTargetsString;

    // submitting form
    document.querySelector('#form-set-final-targets').submit();
  })

});