import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import './styles/app.css';
import './scripts/tag_handler.js';
document.addEventListener('DOMContentLoaded', () => {
  const themeSwitch = document.getElementById('themeSwitch');
  const html = document.documentElement;

  // Initialize the correct theme on load based on user preference
  const currentTheme = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-bs-theme', currentTheme);
  themeSwitch.checked = currentTheme === 'dark';

  themeSwitch.addEventListener('change', () => {
    const newTheme = themeSwitch.checked ? 'dark' : 'light';
    html.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme);
  });
});
