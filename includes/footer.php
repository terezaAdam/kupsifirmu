<footer class="footer">
  <div class="container footer__inner">
    <div class="footer__links">
      <a href="/#proc">Proč my</a>
      <a href="/#postup">Postup</a>
      <a href="/#zahrnuto">Co je v ceně</a>
      <a href="/#faq">FAQ</a>
      <a href="/#kontakt">Kontakt</a>
      <a href="/ochrana-osobnich-udaju.php">Ochrana osobních údajů</a>
    </div>
    <p class="footer__copy">
      &copy; <span id="year"></span> <?= htmlspecialchars($c['footer_copy']) ?> &middot;
      <span class="footer__partner">Součást skupiny <a href="https://equitylegal.cz" target="_blank" rel="noopener">EQUITY LEGAL</a></span>
    </p>
  </div>
</footer>

<script>
document.getElementById('year').textContent = new Date().getFullYear();

const hamburger = document.getElementById('hamburger');
const navMobile = document.getElementById('nav-mobile');
hamburger.addEventListener('click', () => {
  const open = navMobile.classList.toggle('open');
  hamburger.setAttribute('aria-expanded', open);
});
navMobile.querySelectorAll('a').forEach(a => a.addEventListener('click', () => navMobile.classList.remove('open')));

const nav = document.getElementById('nav');
window.addEventListener('scroll', () => {
  nav.classList.toggle('scrolled', window.scrollY > 10);
});

const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: .15 });
document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

document.querySelectorAll('.faq-item__q').forEach(btn => {
  btn.addEventListener('click', () => {
    const item = btn.closest('.faq-item');
    const wasOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
    if (!wasOpen) item.classList.add('open');
  });
});

const form = document.getElementById('contact-form');
const msg = document.getElementById('form-msg');
form?.addEventListener('submit', async (e) => {
  e.preventDefault();
  if (form.botcheck.value) return;
  const data = new FormData(form);
  msg.className = 'form-msg show';
  msg.textContent = 'Odesílám…';
  try {
    const res = await fetch('https://api.web3forms.com/submit', {
      method: 'POST',
      headers: { Accept: 'application/json' },
      body: data,
    });
    const json = await res.json();
    if (json.success) {
      msg.className = 'form-msg show form-msg--ok';
      msg.textContent = 'Děkujeme, ozveme se vám co nejdříve.';
      form.reset();
    } else {
      msg.className = 'form-msg show form-msg--err';
      msg.textContent = json.message || 'Něco se nepovedlo, zkuste to prosím znovu.';
    }
  } catch (err) {
    msg.className = 'form-msg show form-msg--err';
    msg.textContent = 'Formulář se nepodařilo odeslat. Napište nám prosím přímo na e-mail.';
  }
});
</script>

</body>
</html>
