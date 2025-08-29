function toggleMenu() {
  const menu = document.querySelector('#hamburger-nav .menu-links');
  const icon = document.querySelector('#hamburger-nav .hamburger-icon');
  if (!menu || !icon) return;
  menu.classList.toggle('open');
  icon.classList.toggle('open');
}


(() => {
  const phrases = [
    'CSE student at KUET',
    'AI & ML Enthusiast',
    'Deep Learning Explorer',
    'Clean Code Advocate'
  ];
  let idx = 0;
  const el = document.getElementById('rotating-text');
  if (!el) return;
  setInterval(() => {
    idx = (idx + 1) % phrases.length;
    el.classList.remove('fade-in');
    el.classList.add('fade-out');
    setTimeout(() => {
      el.textContent = phrases[idx];
      el.classList.remove('fade-out');
      el.classList.add('fade-in');
    }, 250);
  }, 2500);
})();

// Dark mode toggle
function toggleDarkMode() {
  const isDark = document.documentElement.classList.toggle('dark');
  try { localStorage.setItem('prefers-dark', isDark ? '1' : '0'); } catch (_) {}
}


(() => {
  try {
    const v = localStorage.getItem('prefers-dark');
    if (v === '1') document.documentElement.classList.add('dark');
  } catch (_) {}
})();