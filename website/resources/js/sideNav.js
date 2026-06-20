document.addEventListener('DOMContentLoaded', function () {
  const sideNav = document.getElementById('sideNav');
  const backdrop = document.getElementById('sidenavBackdrop');
  const profileBtn = document.getElementById('profileBtn');
  const profilePopover = document.getElementById('profilePopover');
  const openNavBtn = document.getElementById('openNavBtn');
  const MOBILE_BREAKPOINT = 600;

  // The top .nav bar is a different height on mobile (stacked layout) vs
  // desktop, so measure it directly instead of guessing per-breakpoint.
  // A ResizeObserver (rather than a one-time measurement) keeps this accurate
  // if the nav's real height changes after first paint — e.g. the logo <img>
  // finishing load a moment after this script runs. Without it, --nav-height
  // can be set a few px too small, leaving the sidenav a few px taller than
  // the actual space below the nav and creating a tiny phantom scroll.
  const navEl = document.querySelector('.nav');
  function updateNavHeight() {
    if (navEl) {
      document.documentElement.style.setProperty('--nav-height', `${navEl.getBoundingClientRect().height}px`);
    }
  }
  updateNavHeight();
  if (navEl && window.ResizeObserver) {
    new ResizeObserver(updateNavHeight).observe(navEl);
  } else {
    window.addEventListener('resize', updateNavHeight);
    window.addEventListener('load', updateNavHeight);
  }

  function isMobile() {
    return window.matchMedia(`(max-width: ${MOBILE_BREAKPOINT}px)`).matches;
  }
  
  openNavBtn.addEventListener('click', openNav);

  // Restore saved desktop collapse state (irrelevant on mobile, so skip there).
  // Applied with transitions suppressed first — this is restoring state on
  // load, not a user-triggered toggle, so it shouldn't animate.
  if (!isMobile()) {
    const collapsed = localStorage.getItem('sidenavCollapsed') === 'true';
    sideNav.classList.toggle('collapsed', collapsed);
    sideNav.classList.add('no-transition');
    openNavBtn.classList.add('no-transition');
    openNavBtn.classList.toggle('open', !collapsed);

    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        sideNav.classList.remove('no-transition');
        openNavBtn.classList.remove('no-transition');
      });
    });
  }


  function openNav() {
    if (isMobile()) {
      const opening = !sideNav.classList.contains('mobile-open');
      sideNav.classList.toggle('mobile-open', opening);
      openNavBtn.classList.toggle('open', opening);
      backdrop.classList.toggle('visible', opening);
      document.body.style.overflow = opening ? 'hidden' : '';
      if (!opening) closeProfilePopover();
    } else {
      const collapsed = sideNav.classList.toggle('collapsed');
      openNavBtn.classList.toggle('open', !collapsed);
      localStorage.setItem('sidenavCollapsed', collapsed);
      closeProfilePopover();
    }
  }

  function closeMobileNav() {
    sideNav.classList.remove('mobile-open');
    backdrop.classList.remove('visible');
    document.body.style.overflow = '';
    closeProfilePopover();
  }

  backdrop.addEventListener('click', closeMobileNav);

  // If the window is resized past the breakpoint while the drawer is open, clean up
  window.addEventListener('resize', () => {
    if (!isMobile()) closeMobileNav();
  });

  // --- profile popover ("little box" with Settings / Theme / Logout) ---
  function positionProfilePopover() {
    const rect = profileBtn.getBoundingClientRect();
    profilePopover.style.left = Math.round(rect.left) + 'px';
    // anchor above the button (it sits at the bottom of the sidenav)
    profilePopover.style.bottom = Math.round(window.innerHeight - rect.top + 8) + 'px';
  }

  function openProfilePopover() {
    positionProfilePopover();
    profilePopover.classList.add('open');
    profileBtn.setAttribute('aria-expanded', 'true');
  }

  function closeProfilePopover() {
    profilePopover.classList.remove('open');
    profileBtn.setAttribute('aria-expanded', 'false');
  }

  function toggleProfilePopover(e) {
    e.stopPropagation();
    if (profilePopover.classList.contains('open')) {
      closeProfilePopover();
    } else {
      openProfilePopover();
    }
  }

  profileBtn.addEventListener('click', toggleProfilePopover);

  document.addEventListener('click', (e) => {
    if (!profilePopover.contains(e.target) && e.target !== profileBtn) {
      closeProfilePopover();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeProfilePopover();
  });

  window.addEventListener('resize', closeProfilePopover);
  window.addEventListener('scroll', closeProfilePopover, true);
});