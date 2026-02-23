// ========================================
// FILE UPLOAD HANDLER
// ========================================
const fileInput = document.getElementById("manuscript")
const fileNameSpan = document.querySelector(".file-name")

if (fileInput) {
  fileInput.addEventListener("change", (e) => {
    const fileName = e.target.files[0]?.name || "Aucun fichier sélectionné"
    fileNameSpan.textContent = fileName
  })
}

// ========================================
// FORM SUBMISSION
// ========================================
const submissionForm = document.getElementById("submissionForm")

if (submissionForm) {
  submissionForm.addEventListener("submit", (e) => {
    e.preventDefault()

    // Collect form data
    const formData = {
      firstName: document.getElementById("firstName").value,
      lastName: document.getElementById("lastName").value,
      email: document.getElementById("email").value,
      affiliation: document.getElementById("affiliation").value,
      title: document.getElementById("title").value,
      abstract: document.getElementById("abstract").value,
      keywords: document.getElementById("keywords").value,
      category: document.getElementById("category").value,
    }

    console.log("Form submitted:", formData)

    // Show success message
    alert("Votre article a été soumis avec succès! Vous recevrez un email de confirmation sous peu.")

    // Reset form
    submissionForm.reset()
    fileNameSpan.textContent = "Aucun fichier sélectionné"
  })
}
