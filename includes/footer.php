<footer class="footer">
  <div class="container footer__inner">
    <div class="footer__links">
      <a href="/#proc">Proč my</a>
      <a href="/#postup">Postup</a>
      <a href="/#zahrnuto">Co je v ceně</a>
      <a href="/#faq">FAQ</a>
      <a href="/#kontakt">Kontakt</a>
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
const alertBox = document.getElementById('form-alert');
// Bots fill and submit instantly; a person needs at least a few seconds.
const MIN_FILL_MS = 3000;
const formLoadedAt = Date.now();

const setFieldError = (field, message) => {
  const row = field.closest('.form-row');
  if (!row) return;
  let err = row.querySelector('.form-error-msg');
  if (message) {
    row.classList.add('has-error');
    field.setAttribute('aria-invalid', 'true');
    if (!err) {
      err = document.createElement('span');
      err.className = 'form-error-msg';
      err.id = field.id + '-error';
      // The consent checkbox sits in a flex row with its label; put the message under the label text.
      (field.type === 'checkbox' ? row.querySelector('label') : row).appendChild(err);
      field.setAttribute('aria-describedby', err.id);
    }
    err.textContent = message;
  } else {
    row.classList.remove('has-error');
    field.removeAttribute('aria-invalid');
    err?.remove();
    field.removeAttribute('aria-describedby');
  }
};

const validateField = (field) => {
  if (field.type === 'checkbox') return field.checked ? '' : 'Pro odeslání je potřeba potvrdit souhlas.';
  if (field.required && !field.value.trim()) return 'Vyplňte prosím toto pole.';
  if (field.type === 'email' && !field.validity.valid) return 'Zadejte prosím platný e-mail.';
  return '';
};

const fieldsToCheck = form ? [...form.querySelectorAll('[required]')] : [];
fieldsToCheck.forEach((field) => {
  field.addEventListener(field.type === 'checkbox' ? 'change' : 'input', () => {
    if (field.closest('.form-row')?.classList.contains('has-error')) setFieldError(field, validateField(field));
  });
});

form?.addEventListener('submit', async (e) => {
  e.preventDefault();
  if (form.botcheck.value) return;
  alertBox.hidden = true;

  let firstInvalid = null;
  fieldsToCheck.forEach((field) => {
    const error = validateField(field);
    setFieldError(field, error);
    if (error && !firstInvalid) firstInvalid = field;
  });
  if (firstInvalid) {
    firstInvalid.focus();
    return;
  }

  if (Date.now() - formLoadedAt < MIN_FILL_MS) {
    alertBox.textContent = 'Formulář byl odeslán příliš rychle. Zkuste to prosím za pár sekund znovu.';
    alertBox.hidden = false;
    return;
  }

  const data = new FormData(form);
  const btn = form.querySelector('[type=submit]');
  btn.disabled = true;
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
  btn.disabled = false;
});
</script>

</body>
</html>
