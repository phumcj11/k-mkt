/* ========================================
   K-MKT.COM - GSAP Animations
   Loading, Hero Reveal, ScrollTrigger, Counters
   ======================================== */

/* ---- Lenis Smooth Scroll ---- */
let lenis;

function initLenis() {
  lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    orientation: 'vertical',
    smoothWheel: true,
  });

  lenis.on('scroll', ScrollTrigger.update);

  gsap.ticker.add((time) => {
    lenis.raf(time * 1000);
  });
  gsap.ticker.lagSmoothing(0);
}

/* ---- Loading Screen ---- */
function initLoadingScreen() {
  const loadingScreen = document.getElementById('loading-screen');
  if (!loadingScreen) return;

  const loadingBar = loadingScreen.querySelector('.loading-bar');
  const loadingLogo = loadingScreen.querySelector('.loading-logo');
  const loadingText = loadingScreen.querySelector('.loading-text');

  const tl = gsap.timeline({
    onComplete: () => {
      gsap.to(loadingScreen, {
        opacity: 0,
        duration: 0.6,
        ease: 'power2.inOut',
        onComplete: () => {
          loadingScreen.style.display = 'none';
          document.body.style.overflow = '';
          initHeroAnimation();
        }
      });
    }
  });

  document.body.style.overflow = 'hidden';

  tl.to(loadingLogo, { opacity: 1, y: 0, duration: 0.5, ease: 'power3.out' })
    .to(loadingBar, { width: '100%', duration: 1.8, ease: 'power1.inOut' }, '-=0.3')
    .to(loadingText, { opacity: 0.8, duration: 0.3 }, '-=1.5');
}

/* ---- Hero Animation ---- */
function initHeroAnimation() {
  const heroChars = document.querySelectorAll('.hero-char');
  const heroSub = document.querySelector('.hero-sub');
  const heroCtas = document.querySelector('.hero-ctas');
  const heroBadge = document.querySelector('.hero-badge');
  const statsBar = document.querySelector('.stats-bar');

  if (!heroChars.length) return;

  const tl = gsap.timeline();

  if (heroBadge) {
    tl.from(heroBadge, { opacity: 0, y: 20, duration: 0.5, ease: 'power3.out' });
  }

  tl.to(heroChars, {
    opacity: 1,
    y: 0,
    duration: 0.06,
    stagger: 0.025,
    ease: 'power3.out',
  });

  if (heroSub) {
    tl.from(heroSub, { opacity: 0, y: 30, duration: 0.7, ease: 'power3.out' }, '-=0.2');
  }

  if (heroCtas) {
    tl.from(heroCtas, { opacity: 0, y: 20, duration: 0.5, ease: 'power3.out' }, '-=0.3');
  }

  if (statsBar) {
    tl.from(statsBar, { opacity: 0, y: 30, duration: 0.6, ease: 'power3.out' }, '-=0.2');
  }
}

/* ---- ScrollTrigger General Reveal ---- */
function initScrollReveal() {
  // Reveal Up
  gsap.utils.toArray('.reveal-up').forEach(el => {
    gsap.to(el, {
      opacity: 1,
      y: 0,
      duration: 0.8,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: el,
        start: 'top 88%',
        toggleActions: 'play none none none',
      }
    });
  });

  // Reveal Left
  gsap.utils.toArray('.reveal-left').forEach(el => {
    gsap.to(el, {
      opacity: 1,
      x: 0,
      duration: 0.8,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: el,
        start: 'top 88%',
        toggleActions: 'play none none none',
      }
    });
  });

  // Reveal Right
  gsap.utils.toArray('.reveal-right').forEach(el => {
    gsap.to(el, {
      opacity: 1,
      x: 0,
      duration: 0.8,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: el,
        start: 'top 88%',
        toggleActions: 'play none none none',
      }
    });
  });

  // Reveal Scale
  gsap.utils.toArray('.reveal-scale').forEach(el => {
    gsap.to(el, {
      opacity: 1,
      scale: 1,
      duration: 0.7,
      ease: 'back.out(1.4)',
      scrollTrigger: {
        trigger: el,
        start: 'top 88%',
        toggleActions: 'play none none none',
      }
    });
  });
}

/* ---- Stagger Card Reveal ---- */
function initCardReveal() {
  gsap.utils.toArray('.stagger-reveal').forEach(container => {
    const cards = container.querySelectorAll('.service-card, .industry-card, .blog-card, .zone-card, .case-card-inner, .step-card');
    if (!cards.length) return;

    gsap.fromTo(cards,
      { y: 40 },
      {
        y: 0,
        duration: 0.6,
        stagger: 0.1,
        ease: 'power3.out',
        clearProps: 'transform',
        scrollTrigger: {
          trigger: container,
          start: 'top 92%',
          toggleActions: 'play none none none',
          once: true,
        }
      }
    );
  });
}

/* ---- Animated Counter ---- */
function initCounters() {
  document.querySelectorAll('.counter-value').forEach(counter => {
    const target = parseInt(counter.dataset.target) || 0;
    const suffix = counter.dataset.suffix || '';
    const prefix = counter.dataset.prefix || '';

    ScrollTrigger.create({
      trigger: counter,
      start: 'top 85%',
      onEnter: () => {
        let start = 0;
        const duration = 2000;
        const step = target / (duration / 16);

        const update = () => {
          start = Math.min(start + step, target);
          counter.textContent = prefix + Math.round(start).toLocaleString() + suffix;
          if (start < target) requestAnimationFrame(update);
        };
        update();
      },
      once: true
    });
  });
}

/* ---- Parallax Background ---- */
function initParallax() {
  gsap.utils.toArray('.parallax-bg').forEach(el => {
    gsap.to(el, {
      yPercent: 25,
      ease: 'none',
      scrollTrigger: {
        trigger: el.closest('section') || el.parentElement,
        scrub: true,
      }
    });
  });

  // Orbs parallax
  const orb1 = document.querySelector('.hero-orb-1');
  const orb2 = document.querySelector('.hero-orb-2');

  if (orb1) {
    gsap.to(orb1, {
      y: 80,
      ease: 'none',
      scrollTrigger: { trigger: '#hero', scrub: 1 }
    });
  }

  if (orb2) {
    gsap.to(orb2, {
      y: -60,
      ease: 'none',
      scrollTrigger: { trigger: '#hero', scrub: 1 }
    });
  }
}

/* ---- Horizontal Case Study Scroll ---- */
function initHorizontalScroll() {
  const hTrack = document.querySelector('.h-scroll-track');
  const hContainer = document.querySelector('.h-scroll-container');

  if (!hTrack || !hContainer) return;

  const totalWidth = hTrack.scrollWidth - hContainer.offsetWidth;

  gsap.to(hTrack, {
    x: -totalWidth,
    ease: 'none',
    scrollTrigger: {
      trigger: hContainer,
      pin: true,
      scrub: 1,
      end: () => `+=${totalWidth}`,
      anticipatePin: 1,
    }
  });
}

/* ---- AI Node Diagram Animation ---- */
function initAINodes() {
  const nodes = document.querySelectorAll('.ai-node');
  if (!nodes.length) return;

  gsap.from(nodes, {
    opacity: 0,
    scale: 0.5,
    duration: 0.5,
    stagger: 0.1,
    ease: 'back.out(1.7)',
    scrollTrigger: {
      trigger: '.ai-ecosystem',
      start: 'top 80%',
      toggleActions: 'play none none none',
    }
  });

  const centerNode = document.querySelector('.ai-center-node');
  if (centerNode) {
    gsap.from(centerNode, {
      opacity: 0,
      scale: 0,
      duration: 0.8,
      ease: 'elastic.out(1, 0.5)',
      scrollTrigger: {
        trigger: '.ai-ecosystem',
        start: 'top 80%',
        toggleActions: 'play none none none',
      }
    });
  }
}

/* ---- Section Title Reveal ---- */
function initSectionTitles() {
  gsap.utils.toArray('.section-title').forEach(el => {
    const tag = el.querySelector('.section-tag');
    const h2 = el.querySelector('h2');
    const p = el.querySelector('p');
    const div = el.querySelector('.section-divider');

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: el,
        start: 'top 85%',
        toggleActions: 'play none none none',
      }
    });

    if (tag) tl.from(tag, { opacity: 0, y: 20, duration: 0.5 });
    if (h2) tl.from(h2, { opacity: 0, y: 30, duration: 0.6, ease: 'power3.out' }, '-=0.2');
    if (div) tl.from(div, { scaleX: 0, duration: 0.5, ease: 'power3.out' }, '-=0.3');
    if (p) tl.from(p, { opacity: 0, y: 20, duration: 0.5 }, '-=0.2');
  });
}

/* ---- Timeline Animation ---- */
function initTimeline() {
  const timelineItems = document.querySelectorAll('.timeline-item');
  if (!timelineItems.length) return;

  gsap.from(timelineItems, {
    opacity: 0,
    x: -30,
    duration: 0.6,
    stagger: 0.15,
    ease: 'power3.out',
    scrollTrigger: {
      trigger: '.timeline',
      start: 'top 80%',
      toggleActions: 'play none none none',
    }
  });
}

/* ---- Floating AI Tags ---- */
function initFloatingTags() {
  const tags = document.querySelectorAll('.float-ai-tag');
  if (!tags.length) return;

  gsap.from(tags, {
    opacity: 0,
    y: 30,
    duration: 0.6,
    stagger: 0.15,
    ease: 'power3.out',
    scrollTrigger: {
      trigger: tags[0].closest('section') || tags[0].parentElement,
      start: 'top 85%',
    }
  });
}

/* ---- Score Bars ---- */
function initScoreBars() {
  document.querySelectorAll('.score-item').forEach(item => {
    const fill = item.querySelector('.score-bar-fill');
    const score = parseInt(item.dataset.score) || 0;

    if (!fill) return;

    ScrollTrigger.create({
      trigger: item,
      start: 'top 88%',
      onEnter: () => {
        gsap.to(fill, {
          scaleX: score / 100,
          duration: 1.5,
          ease: 'power2.out',
        });
      },
      once: true,
    });
  });
}

/* ---- Image Reveal ---- */
function initImageReveal() {
  gsap.utils.toArray('.img-reveal').forEach(wrapper => {
    const img = wrapper.querySelector('img');
    if (!img) return;

    gsap.from(wrapper, {
      clipPath: 'inset(0 100% 0 0)',
      duration: 1.2,
      ease: 'power3.inOut',
      scrollTrigger: {
        trigger: wrapper,
        start: 'top 80%',
        toggleActions: 'play none none none',
      }
    });

    gsap.from(img, {
      scale: 1.3,
      duration: 1.5,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: wrapper,
        start: 'top 80%',
        toggleActions: 'play none none none',
      }
    });
  });
}

/* ---- Zone Card Interactive ---- */
function initZoneCards() {
  const cards = document.querySelectorAll('.zone-card');
  cards.forEach(card => {
    card.addEventListener('mouseenter', () => {
      gsap.to(card, { y: -6, duration: 0.3, ease: 'power2.out' });
    });
    card.addEventListener('mouseleave', () => {
      gsap.to(card, { y: 0, duration: 0.4, ease: 'power2.out' });
    });
  });
}

/* ---- Scroll Section Background Shift ---- */
function initBgShift() {
  const sections = document.querySelectorAll('.bg-shift');
  sections.forEach(section => {
    ScrollTrigger.create({
      trigger: section,
      start: 'top center',
      end: 'bottom center',
      onEnter: () => section.classList.add('active'),
      onLeave: () => section.classList.remove('active'),
      onEnterBack: () => section.classList.add('active'),
      onLeaveBack: () => section.classList.remove('active'),
    });
  });
}

/* ---- INIT ALL ---- */
window.addEventListener('load', () => {
  // Register GSAP plugins
  gsap.registerPlugin(ScrollTrigger);

  // Init Lenis first
  if (typeof Lenis !== 'undefined') {
    initLenis();
  }

  // Loading screen (init first)
  initLoadingScreen();

  // After DOMContentLoaded events
  setTimeout(() => {
    initScrollReveal();
    initCardReveal();
    initCounters();
    initParallax();
    initHorizontalScroll();
    initAINodes();
    initSectionTitles();
    initTimeline();
    initFloatingTags();
    initScoreBars();
    initImageReveal();
    initZoneCards();
    initBgShift();
    ScrollTrigger.refresh();
  }, 100);

  // หน้าย่อยไม่มี loading screen — รีเฟรช trigger อีกครั้ง
  if (!document.getElementById('loading-screen')) {
    setTimeout(() => ScrollTrigger.refresh(), 500);
  }
});
