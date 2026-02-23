// ========================================
// SCROLL ANIMATIONS
// ========================================
const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  }
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible")
      }
    })
  }, observerOptions)
  
  // Observe all elements with fade-up class
  document.addEventListener("DOMContentLoaded", () => {
    const fadeElements = document.querySelectorAll(".fade-up")
    fadeElements.forEach((el) => observer.observe(el))
  })
  
  // ========================================
  // MOBILE MENU TOGGLE
  // ========================================
  const mobileMenuToggle = document.querySelector(".mobile-menu-toggle")
  const mobileNav = document.querySelector(".mobile-nav")
  
  if (mobileMenuToggle && mobileNav) {
    mobileMenuToggle.addEventListener("click", () => {
      mobileMenuToggle.classList.toggle("active")
      mobileNav.classList.toggle("active")
    })
  
    // Close mobile menu when clicking on a link
    const mobileNavLinks = mobileNav.querySelectorAll("a")
    mobileNavLinks.forEach((link) => {
      link.addEventListener("click", () => {
        mobileMenuToggle.classList.remove("active")
        mobileNav.classList.remove("active")
      })
    })
  
    // Close mobile menu when clicking outside
    document.addEventListener("click", (event) => {
      if (!mobileMenuToggle.contains(event.target) && !mobileNav.contains(event.target)) {
        mobileMenuToggle.classList.remove("active")
        mobileNav.classList.remove("active")
      }
    })
  }
  
  // ========================================
  // SMOOTH SCROLL FOR ANCHOR LINKS
  // ========================================
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()
      const target = document.querySelector(this.getAttribute("href"))
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        })
      }
    })
  })
  
  // ========================================
  // NEWSLETTER FORM SUBMISSION
  // ========================================
  const newsletterForm = document.querySelector(".newsletter-form")
  if (newsletterForm) {
    newsletterForm.addEventListener("submit", (e) => {
      e.preventDefault()
      const email = newsletterForm.querySelector('input[type="email"]').value
  
      // Simulate form submission
      alert(`Merci de vous être abonné avec l'email: ${email}`)
      newsletterForm.reset()
    })
  }
  
  // ========================================
  // HEADER SCROLL EFFECT
  // ========================================
  let lastScroll = 0
  const header = document.querySelector(".site-header")
  
  window.addEventListener("scroll", () => {
    const currentScroll = window.pageYOffset
  
    if (currentScroll > 100) {
      header.style.boxShadow = "0 4px 20px rgba(0, 0, 0, 0.1)"
    } else {
      header.style.boxShadow = "0 2px 10px rgba(0, 0, 0, 0.05)"
    }
  
    lastScroll = currentScroll
  })
  
  // ========================================
  // PROGRESSIVE UNDERLINE FOR NAV LINKS
  // ========================================
  const navLinks = document.querySelectorAll(".main-nav a")
  navLinks.forEach((link) => {
    link.classList.add("progressive-underline")
  })
  