/* ============================================================
   NOVASPHERE — SCRIPT.JS (PRO EDITION)
   GLOBAL + ADMIN + USER LOGIC
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {

  /* ============================================================
       PAGE LOADER + TRANSITIONS
  ============================================================ */
  const loader = document.getElementById("loader");
  const page = document.getElementById("page");
  const pageTransition = document.getElementById("pageTransition");

  // Make main page visible
  if (page) {
    page.classList.add("page-visible");
  }

  // Fade out the "Booting NovaSphere..." loader
  if (loader) {
    loader.style.opacity = "1";
    setTimeout(() => {
      loader.style.transition = "opacity 0.3s ease";
      loader.style.opacity = "0";
      setTimeout(() => {
        loader.style.display = "none";
      }, 300);
    }, 500);
  }

  // Initial pageTransition animation (small pulse)
  if (pageTransition) {
    pageTransition.classList.add("page-transition-active");
    setTimeout(() => {
      pageTransition.classList.remove("page-transition-active");
    }, 300);
  }

  // Fade-out transition when navigating to another page
  document.querySelectorAll("a[href]").forEach((link) => {
    link.addEventListener("click", (e) => {
      const url = link.getAttribute("href");
      if (!url || url.startsWith("#") || url.startsWith("javascript:")) return;

      if (!pageTransition) return;

      e.preventDefault();
      pageTransition.classList.add("page-transition-active");
      setTimeout(() => {
        window.location.href = url;
      }, 250);
    });
  });


  /* ============================================================
       THEME SWITCHER
  ============================================================ */
  const themeSwitcher = document.getElementById("themeSwitcher");
  if (themeSwitcher) {
    const savedTheme = localStorage.getItem("novasphere_theme");
    if (savedTheme) {
      document.documentElement.setAttribute("data-theme", savedTheme);
      themeSwitcher.value = savedTheme;
    }

    themeSwitcher.addEventListener("change", (e) => {
      const theme = e.target.value;
      document.documentElement.setAttribute("data-theme", theme);
      localStorage.setItem("novasphere_theme", theme);
    });
  }

  /* ============================================================
       NAVBAR DROPDOWN (ACCOUNT)
  ============================================================ */
  const navAccountButton = document.getElementById("navAccountButton");
  const navAccountDropdown = document.getElementById("navAccountDropdown");

  if (navAccountButton && navAccountDropdown) {
    navAccountButton.addEventListener("click", () => {
      navAccountDropdown.classList.toggle("open");
    });

    document.addEventListener("click", (e) => {
      if (!navAccountDropdown.contains(e.target) && e.target !== navAccountButton) {
        navAccountDropdown.classList.remove("open");
      }
    });
  }


  /* ============================================================
       PARALLAX ORBS (HOME)
  ============================================================ */
  const orbs = document.querySelectorAll(".hero-orb");
  if (orbs.length) {
    document.addEventListener("mousemove", (e) => {
      const x = e.clientX / window.innerWidth - 0.5;
      const y = e.clientY / window.innerHeight - 0.5;

      orbs.forEach((orb, i) => {
        const depth = 10 + i * 6;
        orb.style.transform = `translate(${x * -depth}px, ${y * -depth}px)`;
      });
    });
  }


  /* ============================================================
       REVEAL ON SCROLL
  ============================================================ */
  const reveals = document.querySelectorAll(".reveal");
  const revealObserver = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add("reveal-visible");
        obs.unobserve(entry.target);
      });
    },
    { threshold: 0.18 }
  );
  reveals.forEach((el) => revealObserver.observe(el));


  /* ============================================================
       HOVER TILT
  ============================================================ */
  document.querySelectorAll(".hover-tilt").forEach((el) => {
    const strength = 12;

    el.addEventListener("mousemove", (e) => {
      const rect = el.getBoundingClientClientRect?.() || el.getBoundingClientRect();
      const x = e.clientX - rect.left - rect.width / 2;
      const y = e.clientY - rect.top - rect.height / 2;

      const rotX = -(y / rect.height) * strength;
      const rotY = (x / rect.width) * strength;

      el.style.transform = `rotateX(${rotX}deg) rotateY(${rotY}deg)`;
    });

    el.addEventListener("mouseleave", () => {
      el.style.transform = "rotateX(0deg) rotateY(0deg)";
    });
  });


  /* ============================================================
       GALLERY INTERACTION
  ============================================================ */
  const galleryGrid = document.getElementById("galleryGrid");
  if (galleryGrid) {
    const cards = galleryGrid.querySelectorAll(".gallery-card");
    cards.forEach((card) => {
      card.addEventListener("click", () => {
        cards.forEach((c) => c.classList.remove("gallery-card-active"));
        card.classList.add("gallery-card-active");
      });
    });
    if (cards[0]) cards[0].classList.add("gallery-card-active");
  }


  /* ============================================================
       FEEDBACK FORM (UI VALIDATION)
       (Saving handled by feedback2.php)
  ============================================================ */
  const feedbackForm = document.getElementById("feedbackForm");
  if (feedbackForm) {
    feedbackForm.addEventListener("submit", (e) => {
      const msg = feedbackForm.message.value.trim();
      if (msg === "") {
        e.preventDefault();
        alert("Please enter a message.");
      }
    });
  }

});
/* ============================================================
   SCRIPT.JS — PART B
   ADMIN PANEL LOGIC
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {

  const adminSidebar = document.getElementById("adminSidebar");
  const adminContent = document.getElementById("adminContent");
  const sidebarToggleBtn = document.getElementById("sidebarToggleBtn");

  function applyAdminSidebarAuto() {
    if (!adminSidebar || !adminContent) return;

    if (window.innerWidth <= 820) {
      adminSidebar.classList.add("collapsed");
      adminContent.classList.add("collapsed");
    } else {
      const saved = localStorage.getItem("admin_sidebar_mode");
      if (saved === "collapsed") {
        adminSidebar.classList.add("collapsed");
        adminContent.classList.add("collapsed");
      } else {
        adminSidebar.classList.remove("collapsed");
        adminContent.classList.remove("collapsed");
      }
    }
  }

  applyAdminSidebarAuto();
  window.addEventListener("resize", applyAdminSidebarAuto);

  if (sidebarToggleBtn) {
    sidebarToggleBtn.addEventListener("click", () => {
      adminSidebar.classList.toggle("collapsed");
      adminContent.classList.toggle("collapsed");

      if (adminSidebar.classList.contains("collapsed")) {
        localStorage.setItem("admin_sidebar_mode", "collapsed");
      } else {
        localStorage.setItem("admin_sidebar_mode", "expanded");
      }
    });
  }

  const sidebarMode = document.getElementById("sidebarMode");
  const accentColor = document.getElementById("accentColor");
  const saveSettings = document.getElementById("saveSettings");

  function applyAccentColorSetting(mode) {
    const root = document.documentElement;
    if (!mode || mode === "default") {
      root.style.setProperty("--accent", "#00ffff");
      return;
    }
    if (mode === "neon") root.style.setProperty("--accent", "#00eaff");
    if (mode === "cyber") root.style.setProperty("--accent", "#3b8bff");
    if (mode === "sunset") root.style.setProperty("--accent", "#ff784e");
  }

  if (sidebarMode && accentColor) {
    const savedSidebar = localStorage.getItem("admin_sidebar_mode") || "auto";
    const savedAccent  = localStorage.getItem("admin_accent_color") || "default";

    sidebarMode.value = savedSidebar;
    accentColor.value = savedAccent;

    applyAccentColorSetting(savedAccent);
  }

  if (saveSettings) {
    saveSettings.addEventListener("click", () => {
      const sbValue = sidebarMode.value;
      const acValue = accentColor.value;

      localStorage.setItem("admin_sidebar_mode", sbValue);
      localStorage.setItem("admin_accent_color", acValue);

      applyAdminSidebarAuto();
      applyAccentColorSetting(acValue);

      alert("Settings saved!");
    });
  }

  document.querySelectorAll(".sidebar-link").forEach((link) => {
    link.addEventListener("click", (e) => {
      const href = link.getAttribute("href");
      if (!href || !href.startsWith("#")) return;

      e.preventDefault();
      const target = document.querySelector(href);
      if (target) {
        window.scrollTo({
          top: target.offsetTop - 40,
          behavior: "smooth"
        });
      }
    });
  });

  if (typeof Chart !== "undefined") {
    window.addEventListener("resize", () => {
      Chart.helpers.each(Chart.instances, function (id, chart) {
        try { chart.resize(); } catch (e) {}
      });
    });
  }

  const adminSearch = document.querySelector(".admin-search");
  if (adminSearch) {
    adminSearch.addEventListener("focus", () => {
      adminSearch.style.background = "rgba(255,255,255,0.18)";
    });
    adminSearch.addEventListener("blur", () => {
      adminSearch.style.background = "rgba(255,255,255,0.08)";
    });
  }

});

/* ============================================================
   SCRIPT.JS — PART C
   USER DASHBOARD LOGIC
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {

  const userSidebar = document.getElementById("userSidebar");
  const userContent = document.getElementById("userContent");

  function applyUserSidebarAuto() {
    if (!userSidebar || !userContent) return;

    if (window.innerWidth <= 820) {
      userSidebar.classList.add("collapsed");
      userContent.classList.add("collapsed");
    } else {
      userSidebar.classList.remove("collapsed");
      userContent.classList.remove("collapsed");
    }
  }

  applyUserSidebarAuto();
  window.addEventListener("resize", applyUserSidebarAuto);

  const editProfileBtn = document.getElementById("editProfileBtn");
  const editProfileModal = document.getElementById("editProfileModal");
  const closeProfileModalBtn = document.getElementById("closeProfileModal");

  if (editProfileBtn && editProfileModal) {
    editProfileBtn.addEventListener("click", () => {
      editProfileModal.style.display = "flex";
    });

    if (closeProfileModalBtn) {
      closeProfileModalBtn.addEventListener("click", () => {
        editProfileModal.style.display = "none";
      });
    }

    editProfileModal.addEventListener("click", (e) => {
      if (e.target === editProfileModal) {
        editProfileModal.style.display = "none";
      }
    });
  }

  const saveUserPrefsBtn = document.getElementById("saveUserPreferences");

  if (saveUserPrefsBtn) {
    saveUserPrefsBtn.addEventListener("click", () => {
      const theme = document.getElementById("userThemeSelector").value;
      const lang  = document.getElementById("userLangSelector").value;

      const formData = new FormData();
      formData.append("action", "save_preferences");
      formData.append("theme", theme);
      formData.append("language", lang);

      fetch("process_user.php", {
        method: "POST",
        body: formData
      })
      .then(r => r.text())
      .then(() => {
        alert("Preferences saved!");
        location.reload();
      });
    });
  }

  document.querySelectorAll(".sidebar-link").forEach((link) => {
    const targetID = link.getAttribute("href");
    if (!targetID || !targetID.startsWith("#")) return;

    link.addEventListener("click", (e) => {
      const target = document.querySelector(targetID);
      if (!target) return;

      e.preventDefault();
      window.scrollTo({
        top: target.offsetTop - 40,
        behavior: "smooth",
      });
    });
  });

  if (typeof Chart !== "undefined") {
    window.addEventListener("resize", () => {
      Chart.helpers.each(Chart.instances, (id, chart) => {
        try { chart.resize(); } catch(e) {}
      });
    });
  }

  const sections = document.querySelectorAll("a[id]");
  const sidebarLinks = document.querySelectorAll(".sidebar-link");

  const highlightActiveLink = () => {
    let scrollPos = window.scrollY + 120;

    sections.forEach((section) => {
      const top = section.offsetTop;
      const height = section.offsetHeight;

      if (scrollPos >= top && scrollPos < top + height) {
        const id = section.getAttribute("id");
        sidebarLinks.forEach((link) => {
          link.classList.remove("active");
          if (link.getAttribute("href") === `#${id}`) {
            link.classList.add("active");
          }
        });
      }
    });
  };

  window.addEventListener("scroll", highlightActiveLink);
  highlightActiveLink();

});
