const inputElement = document.getElementById('input');
const resultElement = document.getElementById('result');

document.getElementById('form').addEventListener('submit', e => {
  e.preventDefault();
  resultElement.src = './check?url=' + encodeURIComponent(inputElement.value);
  resultElement.style.display = 'block';
}, false);
