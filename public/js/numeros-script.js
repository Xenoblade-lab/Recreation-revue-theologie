// ========================================
// YEAR NAVIGATION
// ========================================
const yearButtons = document.querySelectorAll(".year-btn")
const yearContents = document.querySelectorAll(".year-content")

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible")
        observer.unobserve(entry.target)
      }
    })
  },
  {
    threshold: 0.1,
  },
)

yearButtons.forEach((button) => {
  button.addEventListener("click", (e) => {
    e.preventDefault() // Empêcher tout comportement par défaut
    e.stopPropagation() // Empêcher la propagation
    
    const year = button.getAttribute("data-year")

    // Remove active class from all buttons and contents
    yearButtons.forEach((btn) => btn.classList.remove("active"))
    yearContents.forEach((content) => content.classList.remove("active"))

    // Add active class to clicked button
    button.classList.add("active")

    // Show corresponding year content
    const targetContent = document.querySelector(`.year-content[data-year="${year}"]`)
    if (targetContent) {
      targetContent.classList.add("active")

      // Re-observe fade-up elements in the newly shown content
      const fadeElements = targetContent.querySelectorAll(".fade-up")
      fadeElements.forEach((el) => {
        el.classList.remove("visible")
        observer.observe(el)
      })
    }
    
    return false // Empêcher tout comportement par défaut
  })
})
