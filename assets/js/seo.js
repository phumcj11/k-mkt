/* ========================================
   K-MKT.COM - SEO & Schema.org
   Dynamic Schema Injection
   ======================================== */

(function() {
  'use strict';

  /* ---- Local Business Schema ---- */
  const localBusinessSchema = {
    "@context": "https://schema.org",
    "@type": "MarketingAgency",
    "@id": "https://k-mkt.com/#organization",
    "name": "กาญจน์ตลาด (K-MKT)",
    "alternateName": "K-MKT กาญจนบุรี",
    "url": "https://k-mkt.com",
    "logo": {
      "@type": "ImageObject",
      "url": "https://k-mkt.com/assets/images/logo.png",
      "width": "300",
      "height": "100"
    },
    "image": "https://k-mkt.com/assets/images/og-image.jpg",
    "description": "บริษัทการตลาด Online + Offline + AI Marketing สำหรับธุรกิจในกาญจนบุรี ดูแลแพ รีสอร์ท โรงแรม พูลวิลล่า คาเฟ่ ร้านอาหาร และธุรกิจท่องเที่ยว",
    "foundingDate": "2024",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "กาญจนบุรี",
      "addressLocality": "เมืองกาญจนบุรี",
      "addressRegion": "กาญจนบุรี",
      "postalCode": "71000",
      "addressCountry": "TH"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": "14.0227",
      "longitude": "99.5328"
    },
    "telephone": "+66-XX-XXX-XXXX",
    "priceRange": "฿฿฿",
    "servesCuisine": [],
    "openingHoursSpecification": [
      {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
        "opens": "09:00",
        "closes": "18:00"
      },
      {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": "Saturday",
        "opens": "09:00",
        "closes": "14:00"
      }
    ],
    "sameAs": [
      "https://www.facebook.com/kmkt.kanchanaburi",
      "https://line.me/kmkt",
      "https://www.instagram.com/kmkt.kanchanaburi"
    ],
    "areaServed": {
      "@type": "Place",
      "name": "กาญจนบุรี, ประเทศไทย"
    },
    "hasOfferCatalog": {
      "@type": "OfferCatalog",
      "name": "บริการการตลาด K-MKT",
      "itemListElement": [
        { "@type": "Offer", "itemOffered": { "@type": "Service", "name": "SEO กาญจนบุรี" } },
        { "@type": "Offer", "itemOffered": { "@type": "Service", "name": "Google Ads" } },
        { "@type": "Offer", "itemOffered": { "@type": "Service", "name": "Facebook Ads" } },
        { "@type": "Offer", "itemOffered": { "@type": "Service", "name": "AI Marketing Automation" } },
        { "@type": "Offer", "itemOffered": { "@type": "Service", "name": "Google Map Marketing" } },
        { "@type": "Offer", "itemOffered": { "@type": "Service", "name": "Social Media Marketing" } }
      ]
    }
  };

  /* ---- Website Schema ---- */
  const websiteSchema = {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "K-MKT กาญจน์ตลาด",
    "url": "https://k-mkt.com",
    "potentialAction": {
      "@type": "SearchAction",
      "target": {
        "@type": "EntryPoint",
        "urlTemplate": "https://k-mkt.com/search?q={search_term_string}"
      },
      "query-input": "required name=search_term_string"
    }
  };

  /* ---- Breadcrumb Schema (dynamic) ---- */
  function generateBreadcrumbSchema() {
    const breadcrumb = document.querySelector('.breadcrumb');
    if (!breadcrumb) return null;

    const items = [];
    const links = breadcrumb.querySelectorAll('a');
    const current = breadcrumb.querySelector('span');

    links.forEach((link, i) => {
      items.push({
        "@type": "ListItem",
        "position": i + 1,
        "name": link.textContent.trim(),
        "item": link.href
      });
    });

    if (current) {
      items.push({
        "@type": "ListItem",
        "position": items.length + 1,
        "name": current.textContent.trim()
      });
    }

    return {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": items
    };
  }

  /* ---- FAQ Schema (dynamic) ---- */
  function generateFAQSchema() {
    const faqItems = document.querySelectorAll('.faq-item');
    if (!faqItems.length) return null;

    const mainEntity = [];
    faqItems.forEach(item => {
      const q = item.querySelector('.faq-question')?.textContent?.trim();
      const a = item.querySelector('.faq-answer-inner')?.textContent?.trim();
      if (q && a) {
        mainEntity.push({
          "@type": "Question",
          "name": q,
          "acceptedAnswer": {
            "@type": "Answer",
            "text": a
          }
        });
      }
    });

    if (!mainEntity.length) return null;

    return {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": mainEntity
    };
  }

  /* ---- Article Schema ---- */
  function generateArticleSchema() {
    const articleEl = document.querySelector('[data-article]');
    if (!articleEl) return null;

    return {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": document.querySelector('h1')?.textContent?.trim() || document.title,
      "author": {
        "@type": "Organization",
        "name": "K-MKT กาญจน์ตลาด"
      },
      "publisher": {
        "@type": "Organization",
        "name": "K-MKT กาญจน์ตลาด",
        "logo": {
          "@type": "ImageObject",
          "url": "https://k-mkt.com/assets/images/logo.png"
        }
      },
      "datePublished": articleEl.dataset.date || new Date().toISOString().split('T')[0],
      "dateModified": articleEl.dataset.modified || new Date().toISOString().split('T')[0],
      "image": "https://k-mkt.com/assets/images/og-image.jpg",
      "url": window.location.href,
      "inLanguage": "th-TH"
    };
  }

  /* ---- Inject Schemas ---- */
  function injectSchema(schema) {
    if (!schema) return;
    const script = document.createElement('script');
    script.type = 'application/ld+json';
    script.textContent = JSON.stringify(schema, null, 2);
    document.head.appendChild(script);
  }

  /* ---- Initialize ---- */
  function init() {
    // Always inject base schemas
    injectSchema(localBusinessSchema);
    injectSchema(websiteSchema);

    // Inject page-specific schemas
    const breadcrumbSchema = generateBreadcrumbSchema();
    if (breadcrumbSchema) injectSchema(breadcrumbSchema);

    const faqSchema = generateFAQSchema();
    if (faqSchema) injectSchema(faqSchema);

    const articleSchema = generateArticleSchema();
    if (articleSchema) injectSchema(articleSchema);

    // Update canonical
    const canonical = document.querySelector('link[rel="canonical"]');
    if (!canonical) {
      const link = document.createElement('link');
      link.rel = 'canonical';
      link.href = window.location.origin + window.location.pathname;
      document.head.appendChild(link);
    }

    // Update OG URL dynamically
    const ogUrl = document.querySelector('meta[property="og:url"]');
    if (ogUrl && !ogUrl.content.includes('k-mkt.com')) {
      ogUrl.content = 'https://k-mkt.com' + window.location.pathname;
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
