export type Locale = "fr" | "en"

export const defaultLocale: Locale = "fr"

export const translations = {
  fr: {
    // Navigation
    nav: {
      home: "Accueil",
      about: "A propos",
      committee: "Comite editorial",
      publications: "Publications",
      archives: "Archives",
      submissions: "Soumissions",
      policy: "Politique editoriale",
      contact: "Contact",
      faq: "FAQ",
      login: "Connexion",
      language: "EN",
    },
    // Homepage
    hero: {
      title: "Revue de la Faculte de Theologie",
      subtitle: "Universite Protestante au Congo",
      description:
        "Publication scientifique de reference en theologie, contribuant au dialogue academique et a la reflexion theologique en Afrique et dans le monde.",
      latestIssue: "Dernier numero",
      browseArchives: "Parcourir les archives",
      submitArticle: "Soumettre un article",
    },
    // Sections
    sections: {
      latestArticles: "Articles recents",
      aboutReview: "A propos de la revue",
      aboutDescription:
        "La Revue de la Faculte de Theologie de l'UPC est une publication scientifique a comite de lecture, consacree a la recherche theologique contextuelle et interdisciplinaire.",
      mission: "Mission",
      missionText:
        "Promouvoir la recherche theologique de qualite et le dialogue academique en Afrique et dans le monde, dans la tradition protestante reformee.",
      peerReview: "Evaluation par les pairs",
      peerReviewText:
        "Chaque article soumis est evalue en double aveugle par au moins deux experts du domaine, garantissant la rigueur scientifique.",
      openAccess: "Acces ouvert",
      openAccessText:
        "Nous croyons au partage du savoir. Les articles publies sont accessibles a tous sous licence Creative Commons.",
      categories: "Domaines de recherche",
      systematicTheology: "Theologie Systematique",
      biblicalStudies: "Etudes Bibliques",
      christianEthics: "Ethique Chretienne",
      churchHistory: "Histoire de l'Eglise",
      practicalTheology: "Theologie Pratique",
      callForPapers: "Appel a contributions",
      callForPapersText:
        "La revue accueille des articles originaux en francais et en anglais. Consultez nos instructions aux auteurs pour soumettre votre manuscrit.",
      learnMore: "En savoir plus",
      viewAll: "Voir tout",
      readMore: "Lire la suite",
      downloadPdf: "Telecharger PDF",
      volume: "Volume",
      issue: "Numero",
      pages: "Pages",
      publishedOn: "Publie le",
    },
    // Footer
    footer: {
      description:
        "Publication scientifique de la Faculte de Theologie de l'Universite Protestante au Congo.",
      quickLinks: "Liens rapides",
      forAuthors: "Pour les auteurs",
      authorGuidelines: "Instructions aux auteurs",
      submitManuscript: "Soumettre un manuscrit",
      subscriptions: "Abonnements",
      followUs: "Suivez-nous",
      contactUs: "Contactez-nous",
      address: "Kinshasa, Republique Democratique du Congo",
      rights: "Tous droits reserves.",
      privacy: "Confidentialite",
      terms: "Conditions d'utilisation",
      issn: "ISSN",
    },
  },
  en: {
    // Navigation
    nav: {
      home: "Home",
      about: "About",
      committee: "Editorial Board",
      publications: "Publications",
      archives: "Archives",
      submissions: "Submissions",
      policy: "Editorial Policy",
      contact: "Contact",
      faq: "FAQ",
      login: "Login",
      language: "FR",
    },
    // Homepage
    hero: {
      title: "Journal of the Faculty of Theology",
      subtitle: "Protestant University in Congo",
      description:
        "A leading scientific publication in theology, contributing to academic dialogue and theological reflection in Africa and worldwide.",
      latestIssue: "Latest Issue",
      browseArchives: "Browse Archives",
      submitArticle: "Submit an Article",
    },
    // Sections
    sections: {
      latestArticles: "Recent Articles",
      aboutReview: "About the Journal",
      aboutDescription:
        "The Journal of the Faculty of Theology at UPC is a peer-reviewed scientific publication dedicated to contextual and interdisciplinary theological research.",
      mission: "Mission",
      missionText:
        "To promote quality theological research and academic dialogue in Africa and worldwide, in the Reformed Protestant tradition.",
      peerReview: "Peer Review",
      peerReviewText:
        "Every submitted article undergoes double-blind review by at least two domain experts, ensuring scientific rigor.",
      openAccess: "Open Access",
      openAccessText:
        "We believe in sharing knowledge. Published articles are accessible to all under a Creative Commons license.",
      categories: "Research Areas",
      systematicTheology: "Systematic Theology",
      biblicalStudies: "Biblical Studies",
      christianEthics: "Christian Ethics",
      churchHistory: "Church History",
      practicalTheology: "Practical Theology",
      callForPapers: "Call for Papers",
      callForPapersText:
        "The journal welcomes original articles in French and English. Review our author guidelines to submit your manuscript.",
      learnMore: "Learn More",
      viewAll: "View All",
      readMore: "Read More",
      downloadPdf: "Download PDF",
      volume: "Volume",
      issue: "Issue",
      pages: "Pages",
      publishedOn: "Published on",
    },
    // Footer
    footer: {
      description:
        "Scientific publication of the Faculty of Theology at the Protestant University in Congo.",
      quickLinks: "Quick Links",
      forAuthors: "For Authors",
      authorGuidelines: "Author Guidelines",
      submitManuscript: "Submit a Manuscript",
      subscriptions: "Subscriptions",
      followUs: "Follow Us",
      contactUs: "Contact Us",
      address: "Kinshasa, Democratic Republic of Congo",
      rights: "All rights reserved.",
      privacy: "Privacy",
      terms: "Terms of Use",
      issn: "ISSN",
    },
  },
} as const

export type TranslationKey = typeof translations.fr
