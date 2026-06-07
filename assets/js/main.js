/* ========================================
   K-MKT.COM - Main JavaScript
   Header, Mobile Menu, Floating Bar, Cursor
   ======================================== */

document.addEventListener('DOMContentLoaded', () => {

  /* ---- Scroll Progress Bar ---- */
  const progressBar = document.getElementById('scroll-progress');
  if (progressBar) {
    window.addEventListener('scroll', () => {
      const scrollTop = window.scrollY;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      const progress = scrollTop / docHeight;
      progressBar.style.transform = `scaleX(${progress})`;
    });
  }

  /* ---- Sticky Header ---- */
  const header = document.getElementById('main-header');
  if (header) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 60) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });
  }

  /* ---- Mobile Menu ---- */
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobile-menu');
  const mobileClose = document.getElementById('mobile-close');

  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('open');
      mobileMenu.classList.toggle('open');
      document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
    });
  }

  if (mobileClose && mobileMenu) {
    mobileClose.addEventListener('click', () => {
      mobileMenu.classList.remove('open');
      if (hamburger) hamburger.classList.remove('open');
      document.body.style.overflow = '';
    });
  }

  // Close on link click
  document.querySelectorAll('.mobile-nav-link').forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu.classList.remove('open');
      if (hamburger) hamburger.classList.remove('open');
      document.body.style.overflow = '';
    });
  });

  /* ---- Custom Cursor ---- */
  const cursorDot = document.getElementById('cursor-dot');
  const cursorRing = document.getElementById('cursor-ring');
  const cursorGlow = document.getElementById('cursor-glow');

  if (cursorDot && window.innerWidth > 768) {
    let mouseX = 0, mouseY = 0;
    let ringX = 0, ringY = 0;

    document.addEventListener('mousemove', (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      cursorDot.style.left = mouseX + 'px';
      cursorDot.style.top = mouseY + 'px';
      if (cursorGlow) {
        cursorGlow.style.left = mouseX + 'px';
        cursorGlow.style.top = mouseY + 'px';
      }
    });

    function animateRing() {
      ringX += (mouseX - ringX) * 0.12;
      ringY += (mouseY - ringY) * 0.12;
      if (cursorRing) {
        cursorRing.style.left = ringX + 'px';
        cursorRing.style.top = ringY + 'px';
      }
      requestAnimationFrame(animateRing);
    }
    animateRing();

    // Scale on hover
    const interactiveEls = document.querySelectorAll('a, button, .btn, .service-card, .industry-card, .zone-card, .faq-question, .blog-card');
    interactiveEls.forEach(el => {
      el.addEventListener('mouseenter', () => {
        cursorDot.style.transform = 'translate(-50%,-50%) scale(2.5)';
        if (cursorRing) {
          cursorRing.style.transform = 'translate(-50%,-50%) scale(1.5)';
          cursorRing.style.borderColor = 'rgba(212,175,55,0.8)';
        }
      });
      el.addEventListener('mouseleave', () => {
        cursorDot.style.transform = 'translate(-50%,-50%) scale(1)';
        if (cursorRing) {
          cursorRing.style.transform = 'translate(-50%,-50%) scale(1)';
          cursorRing.style.borderColor = 'rgba(212,175,55,0.6)';
        }
      });
    });
  }

  /* ---- Magnetic Buttons ---- */
  document.querySelectorAll('.magnetic').forEach(btn => {
    btn.addEventListener('mousemove', (e) => {
      const rect = btn.getBoundingClientRect();
      const cx = rect.left + rect.width / 2;
      const cy = rect.top + rect.height / 2;
      const dx = (e.clientX - cx) * 0.3;
      const dy = (e.clientY - cy) * 0.3;
      btn.style.transform = `translate(${dx}px, ${dy}px)`;
    });
    btn.addEventListener('mouseleave', () => {
      btn.style.transform = 'translate(0,0)';
      btn.style.transition = 'transform 0.5s cubic-bezier(0.4,0,0.2,1)';
      setTimeout(() => { btn.style.transition = ''; }, 500);
    });
  });

  /* ---- FAQ Accordion ---- */
  document.querySelectorAll('.faq-question').forEach(q => {
    q.addEventListener('click', () => {
      const item = q.closest('.faq-item');
      const answer = item.querySelector('.faq-answer');
      const isOpen = item.classList.contains('open');

      // Close all
      document.querySelectorAll('.faq-item.open').forEach(openItem => {
        openItem.classList.remove('open');
        openItem.querySelector('.faq-answer').style.maxHeight = '0';
      });

      // Open clicked
      if (!isOpen) {
        item.classList.add('open');
        answer.style.maxHeight = answer.scrollHeight + 'px';
      }
    });
  });

  /* ---- Score Bars Animation ---- */
  const observeScoreBars = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const fill = entry.target.querySelector('.score-bar-fill');
        const score = entry.target.dataset.score;
        if (fill && score) {
          setTimeout(() => {
            fill.style.transform = `scaleX(${score / 100})`;
          }, 200);
        }
        observeScoreBars.unobserve(entry.target);
      }
    });
  }, { threshold: 0.3 });

  document.querySelectorAll('.score-bar-wrap').forEach(bar => {
    observeScoreBars.observe(bar.closest('[data-score]') || bar.parentElement);
  });

  /* ---- Form Submission ---- */
  const apiBase = window.location.pathname.includes('/k-mkt/') ? '/k-mkt/api/submit-form.php' : '/api/submit-form.php';

  const forms = document.querySelectorAll('form[data-form]');
  forms.forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = form.querySelector('button[type="submit"]');
      const formType = form.getAttribute('data-form');
      const fd = new FormData(form);
      const payload = { form_type: formType };

      fd.forEach((val, key) => { payload[key] = val; });

      if (btn) {
        const orig = btn.textContent;
        btn.textContent = 'กำลังส่ง...';
        btn.disabled = true;
        try {
          const res = await fetch(apiBase, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
          });
          const data = await res.json();
          if (data.success) {
            btn.textContent = '✓ ส่งข้อมูลแล้ว';
            btn.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
            showToast(data.message || 'ขอบคุณ! ทีมงานกาญจน์ตลาดจะติดต่อกลับภายใน 24 ชั่วโมง 🙏');
            form.reset();
          } else {
            showToast(data.message || 'ส่งไม่สำเร็จ กรุณาลองใหม่');
            btn.textContent = orig;
          }
        } catch (err) {
          showToast('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
          btn.textContent = orig;
        }
        setTimeout(() => {
          btn.textContent = orig;
          btn.style.background = '';
          btn.disabled = false;
        }, 4000);
      }
    });
  });

  /* ---- Toast Notification ---- */
  function showToast(msg) {
    let toast = document.getElementById('toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'toast';
      document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 4000);
  }

  /* ---- Kanchanaburi Zone Cards Hover ---- */
  document.querySelectorAll('.zone-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
      const info = card.querySelector('.zone-info');
      if (info) {
        info.style.maxHeight = info.scrollHeight + 'px';
        info.style.opacity = '1';
      }
    });
    card.addEventListener('mouseleave', () => {
      const info = card.querySelector('.zone-info');
      if (info) {
        info.style.maxHeight = '0';
        info.style.opacity = '0';
      }
    });
  });

  /* ---- Active Nav Link ---- */
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (href && href === currentPage) {
      link.style.color = 'var(--gold)';
    }
  });

  /* ---- Lazy Loading Images ---- */
  const lazyImages = document.querySelectorAll('img[data-src]');
  const imageObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.dataset.src;
        img.removeAttribute('data-src');
        imageObserver.unobserve(img);
      }
    });
  }, { threshold: 0.1 });

  lazyImages.forEach(img => imageObserver.observe(img));

  /* ---- Floating AI Tags Random Animation ---- */
  document.querySelectorAll('.float-ai-tag').forEach((tag, i) => {
    const randomDelay = (i * 0.6) % 3;
    tag.style.animationDelay = `${randomDelay}s`;
  });

});
