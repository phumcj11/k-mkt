/**
 * โหลดข้อมูลติดต่อจาก API แล้วอัปเดตทุก element ที่มี data-contact
 */
(function () {
  'use strict';

  const base = document.querySelector('base')?.href || '';
  const path = window.location.pathname.includes('/k-mkt/') ? '/k-mkt/api/settings.php' : '/api/settings.php';

  fetch(path)
    .then(r => r.json())
    .then(res => {
      if (!res.success && !res.data) return;
      const d = res.data;

      document.querySelectorAll('[data-contact]').forEach(el => {
        const key = el.getAttribute('data-contact');
        const val = d[key];
        if (!val) return;

        if (el.tagName === 'A') {
          if (key === 'phone_tel') el.href = 'tel:' + val;
          else if (key === 'line_url') el.href = val;
          else if (key === 'email') el.href = 'mailto:' + val;
          else if (key === 'facebook_url' || key === 'messenger_url') el.href = val;
        }

        if (el.hasAttribute('data-contact-text')) {
          el.textContent = val;
        } else if (key === 'line_id') {
          el.textContent = el.textContent.includes('LINE') ? 'LINE: ' + val : val;
        } else if (key === 'phone') {
          el.textContent = val;
        } else if (key === 'email') {
          el.textContent = val;
        }
      });

      document.querySelectorAll('[data-contact-href]').forEach(el => {
        const key = el.getAttribute('data-contact-href');
        if (d[key]) el.href = key === 'phone_tel' ? 'tel:' + d[key] : d[key];
      });
    })
    .catch(() => {});
})();
