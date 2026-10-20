'use strict';
function renderPreview() {
  const raw = window.location.hash.slice(1);
  let message;
  try {
    message = raw ? decodeURIComponent(raw) : 'Welcome to CampusConnect';
  } catch (_err) {
    message = 'Invalid preview text';
  }
  document.getElementById('announcement').textContent = message.slice(0, 1000);
}
window.addEventListener('hashchange', renderPreview);
renderPreview();
