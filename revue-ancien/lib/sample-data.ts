import type { Locale } from "@/lib/i18n"

export interface Article {
  id: number
  titleFr: string
  titleEn: string
  abstractFr: string
  abstractEn: string
  authorFr: string
  authorEn: string
  affiliation: string
  category: string
  volume: number
  issue: number
  pages: string
  doi?: string
  date: string
  keywords: string[]
}

export interface Volume {
  year: number
  number: number
  description: string
  descriptionEn: string
  issues: Issue[]
}

export interface Issue {
  id: number
  title: string
  titleEn: string
  description: string
  descriptionEn: string
  date: string
  articleCount: number
}

export const categoryLabels: Record<string, Record<Locale, string>> = {
  "systematic-theology": { fr: "Theologie Systematique", en: "Systematic Theology" },
  "biblical-studies": { fr: "Etudes Bibliques", en: "Biblical Studies" },
  "christian-ethics": { fr: "Ethique Chretienne", en: "Christian Ethics" },
  "church-history": { fr: "Histoire de l'Eglise", en: "Church History" },
  "practical-theology": { fr: "Theologie Pratique", en: "Practical Theology" },
}

export const sampleArticles: Article[] = [
  {
    id: 1,
    titleFr: "La pneumatologie dans la tradition reformee africaine : perspectives contemporaines",
    titleEn: "Pneumatology in the African Reformed Tradition: Contemporary Perspectives",
    abstractFr: "Cet article explore les developpements recents de la pneumatologie dans le contexte de la theologie reformee en Afrique subsaharienne. En analysant les contributions des theologiens africains contemporains, il met en lumiere les tensions fecondes entre la tradition reformee classique et les expressions charismatiques africaines.",
    abstractEn: "This article explores recent developments in pneumatology within the context of Reformed theology in sub-Saharan Africa. By analyzing the contributions of contemporary African theologians, it highlights the fruitful tensions between classical Reformed tradition and African charismatic expressions.",
    authorFr: "Prof. Jean-Baptiste Muamba",
    authorEn: "Prof. Jean-Baptiste Muamba",
    affiliation: "Universite Protestante au Congo",
    category: "systematic-theology",
    volume: 28,
    issue: 1,
    pages: "15-42",
    doi: "10.5678/rft.2025.001",
    date: "2025-09-15",
    keywords: ["pneumatologie", "tradition reformee", "Afrique", "charismatisme"],
  },
  {
    id: 2,
    titleFr: "Hermeneutique contextuelle et lecture africaine du Nouveau Testament",
    titleEn: "Contextual Hermeneutics and African Reading of the New Testament",
    abstractFr: "Cette etude propose une methodologie hermeneutique qui integre les perspectives culturelles africaines dans l'interpretation des textes neotestamentaires. L'auteur examine comment les traditions orales et les structures communautaires africaines enrichissent la comprehension des paraboles de Jesus.",
    abstractEn: "This study proposes a hermeneutical methodology that integrates African cultural perspectives into the interpretation of New Testament texts. The author examines how African oral traditions and communal structures enrich our understanding of Jesus' parables.",
    authorFr: "Dr. Marie-Claire Lunda",
    authorEn: "Dr. Marie-Claire Lunda",
    affiliation: "Universite Protestante au Congo",
    category: "biblical-studies",
    volume: 28,
    issue: 1,
    pages: "43-68",
    doi: "10.5678/rft.2025.002",
    date: "2025-09-15",
    keywords: ["hermeneutique", "contextualisation", "Nouveau Testament", "Afrique"],
  },
  {
    id: 3,
    titleFr: "Ethique chretienne et justice sociale en Republique Democratique du Congo",
    titleEn: "Christian Ethics and Social Justice in the Democratic Republic of Congo",
    abstractFr: "Face aux defis socio-politiques contemporains de la RDC, cet article examine le role de l'ethique chretienne dans la promotion de la justice sociale. L'auteur propose un cadre ethique fonde sur la theologie de la liberation et la tradition prophetique protestante.",
    abstractEn: "Facing the contemporary socio-political challenges of the DRC, this article examines the role of Christian ethics in promoting social justice. The author proposes an ethical framework grounded in liberation theology and the Protestant prophetic tradition.",
    authorFr: "Dr. Patrick Kasongo",
    authorEn: "Dr. Patrick Kasongo",
    affiliation: "Universite de Lubumbashi",
    category: "christian-ethics",
    volume: 28,
    issue: 1,
    pages: "69-94",
    doi: "10.5678/rft.2025.003",
    date: "2025-09-15",
    keywords: ["ethique", "justice sociale", "RDC", "theologie de la liberation"],
  },
  {
    id: 4,
    titleFr: "Le protestantisme au Congo : de la mission a l'Eglise autonome (1878-1960)",
    titleEn: "Protestantism in Congo: From Mission to Autonomous Church (1878-1960)",
    abstractFr: "Cette etude historique retrace l'evolution du protestantisme au Congo depuis l'arrivee des premiers missionnaires jusqu'a l'autonomie des Eglises congolaises. Elle examine les facteurs theologiques, politiques et culturels qui ont faconne cette transition.",
    abstractEn: "This historical study traces the evolution of Protestantism in Congo from the arrival of the first missionaries to the autonomy of Congolese churches. It examines the theological, political, and cultural factors that shaped this transition.",
    authorFr: "Prof. Andre Lubamba",
    authorEn: "Prof. Andre Lubamba",
    affiliation: "Universite Protestante au Congo",
    category: "church-history",
    volume: 27,
    issue: 2,
    pages: "101-134",
    doi: "10.5678/rft.2024.008",
    date: "2024-06-20",
    keywords: ["protestantisme", "Congo", "mission", "autonomie ecclesiale"],
  },
  {
    id: 5,
    titleFr: "Pastorale et accompagnement des personnes endeuillees dans le contexte congolais",
    titleEn: "Pastoral Care and Bereavement Support in the Congolese Context",
    abstractFr: "Cet article examine les pratiques pastorales d'accompagnement des personnes endeuillees en RDC, en dialogue avec les traditions culturelles locales et la theologie pastorale contemporaine.",
    abstractEn: "This article examines pastoral practices for supporting bereaved persons in the DRC, in dialogue with local cultural traditions and contemporary pastoral theology.",
    authorFr: "Dr. Josephine Mwamba",
    authorEn: "Dr. Josephine Mwamba",
    affiliation: "Universite Protestante au Congo",
    category: "practical-theology",
    volume: 27,
    issue: 2,
    pages: "135-158",
    doi: "10.5678/rft.2024.009",
    date: "2024-06-20",
    keywords: ["pastorale", "deuil", "contexte congolais", "accompagnement"],
  },
  {
    id: 6,
    titleFr: "Christologie africaine : entre universalite et particularite",
    titleEn: "African Christology: Between Universality and Particularity",
    abstractFr: "L'article examine les differentes approches christologiques developpees par les theologiens africains, en evaluant comment elles articulent l'universalite du message chretien et la particularite des cultures africaines.",
    abstractEn: "This article examines the various Christological approaches developed by African theologians, evaluating how they articulate the universality of the Christian message and the particularity of African cultures.",
    authorFr: "Prof. Simon Kayembe",
    authorEn: "Prof. Simon Kayembe",
    affiliation: "Universite de Kinshasa",
    category: "systematic-theology",
    volume: 27,
    issue: 1,
    pages: "7-35",
    doi: "10.5678/rft.2024.001",
    date: "2024-01-15",
    keywords: ["christologie", "Afrique", "inculturation", "theologie contextuelle"],
  },
]

export const sampleVolumes: Volume[] = [
  {
    year: 2025,
    number: 28,
    description: "Volume 28 - Annee 2025",
    descriptionEn: "Volume 28 - Year 2025",
    issues: [
      { id: 1, title: "Numero 1", titleEn: "Issue 1", description: "Theologie et contexte africain contemporain", descriptionEn: "Theology and the Contemporary African Context", date: "2025-09-15", articleCount: 3 },
    ],
  },
  {
    year: 2024,
    number: 27,
    description: "Volume 27 - Annee 2024",
    descriptionEn: "Volume 27 - Year 2024",
    issues: [
      { id: 3, title: "Numero 2", titleEn: "Issue 2", description: "Theologie pratique et engagement social", descriptionEn: "Practical Theology and Social Engagement", date: "2024-06-20", articleCount: 2 },
      { id: 2, title: "Numero 1", titleEn: "Issue 1", description: "Christologie et identite africaine", descriptionEn: "Christology and African Identity", date: "2024-01-15", articleCount: 1 },
    ],
  },
  {
    year: 2023,
    number: 26,
    description: "Volume 26 - Annee 2023",
    descriptionEn: "Volume 26 - Year 2023",
    issues: [
      { id: 5, title: "Numero 2", titleEn: "Issue 2", description: "Perspectives oecumeniques", descriptionEn: "Ecumenical Perspectives", date: "2023-06-15", articleCount: 5 },
      { id: 4, title: "Numero 1", titleEn: "Issue 1", description: "Hermeneutique biblique africaine", descriptionEn: "African Biblical Hermeneutics", date: "2023-01-20", articleCount: 6 },
    ],
  },
]
