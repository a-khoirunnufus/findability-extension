const headerQuicknav = document.querySelector('#quicknav');
const bodyQuicknav = headerQuicknav.nextElementSibling;

const headerUserTesting = document.querySelector('#user-testing');
const bodyUserTesting = headerUserTesting.nextElementSibling;

const headerUserTestingCurrent = document.querySelector('#user-testing-current');
const bodyUserTestingCurrent = headerUserTestingCurrent.nextElementSibling;
// const wrapperBodyUserTestingCurrent = document.querySelector('#wrapper-current-task');

const headerUserTestingList = document.querySelector('#user-testing-list');
const bodyUserTestingList = headerUserTestingList.nextElementSibling;
// const wrapperBodyUserTestingList = document.querySelector('#wrapper-task-list');

const spinner = document.createElement('div');
spinner.className = 'spinner-border';
spinner.innerHTML = '<span class="visually-hidden">Loading...</span>';

export {
  headerQuicknav,
  bodyQuicknav,
  headerUserTesting,
  bodyUserTesting,
  headerUserTestingCurrent,
  bodyUserTestingCurrent,
  headerUserTestingList,
  bodyUserTestingList,
  spinner,
};