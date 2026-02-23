"use client"

import { PageLayout } from "@/components/page-layout"
import { useI18n } from "@/components/i18n-provider"
import { BookOpen, Target, Eye, Globe } from "lucide-react"

const content = {
  fr: {
    title: "A propos de la revue",
    subtitle: "Decouvrez l'histoire, la mission et la vision de notre publication scientifique.",
    historyTitle: "Historique",
    historyP1:
      "La Revue de la Faculte de Theologie de l'Universite Protestante au Congo (UPC) est une publication scientifique fondee pour promouvoir la recherche theologique de qualite en Afrique francophone et au-dela.",
    historyP2:
      "Depuis sa creation, la revue a publie des centaines d'articles couvrant un large spectre de la theologie : theologie systematique, etudes bibliques, ethique chretienne, histoire de l'Eglise et theologie pratique.",
    historyP3:
      "La revue s'inscrit dans la tradition protestante reformee tout en etant ouverte a l'oecumenisme et au dialogue interconfessionnel. Elle accueille des contributions de chercheurs du monde entier.",
    missionTitle: "Notre mission",
    missionText:
      "Promouvoir la recherche theologique de qualite, encourager le dialogue academique et contribuer au developpement de la pensee theologique contextuelle africaine dans une perspective mondiale.",
    visionTitle: "Notre vision",
    visionText:
      "Devenir une revue de reference en theologie en Afrique, reconnue pour son excellence academique, sa rigueur scientifique et sa contribution au dialogue interconfessionnel et interculturel.",
    valuesTitle: "Nos valeurs",
    values: [
      { title: "Excellence academique", text: "Nous maintenons les plus hauts standards de recherche et de publication scientifique." },
      { title: "Contextualite", text: "Nous valorisons la reflexion theologique ancree dans le contexte africain tout en portee universelle." },
      { title: "Ouverture", text: "Nous accueillons la diversite des perspectives theologiques et confessionnelles." },
      { title: "Integrite", text: "Nous respectons les principes ethiques de la recherche et de la publication academique." },
    ],
    scopeTitle: "Portee et domaines",
    scopeText:
      "La revue publie des articles de recherche originaux, des notes de recherche, des comptes-rendus et des traductions dans les domaines suivants :",
    domains: [
      "Theologie Systematique et Dogmatique",
      "Etudes Bibliques (Ancien et Nouveau Testament)",
      "Ethique Chretienne et Theologie Morale",
      "Histoire de l'Eglise et du Christianisme",
      "Theologie Pratique et Pastorale",
      "Missiologie et Theologie Interculturelle",
      "Theologie et Dialogue Interreligieux",
    ],
  },
  en: {
    title: "About the Journal",
    subtitle: "Discover the history, mission and vision of our scientific publication.",
    historyTitle: "History",
    historyP1:
      "The Journal of the Faculty of Theology at the Protestant University in Congo (UPC) is a scientific publication founded to promote quality theological research in Francophone Africa and beyond.",
    historyP2:
      "Since its founding, the journal has published hundreds of articles covering a broad spectrum of theology: systematic theology, biblical studies, Christian ethics, church history, and practical theology.",
    historyP3:
      "The journal is rooted in the Reformed Protestant tradition while remaining open to ecumenism and interdenominational dialogue. It welcomes contributions from researchers worldwide.",
    missionTitle: "Our Mission",
    missionText:
      "To promote quality theological research, encourage academic dialogue, and contribute to the development of contextual African theological thought from a global perspective.",
    visionTitle: "Our Vision",
    visionText:
      "To become a leading theology journal in Africa, recognized for academic excellence, scientific rigor, and contribution to interdenominational and intercultural dialogue.",
    valuesTitle: "Our Values",
    values: [
      { title: "Academic Excellence", text: "We maintain the highest standards of research and scientific publication." },
      { title: "Contextuality", text: "We value theological reflection rooted in the African context with universal relevance." },
      { title: "Openness", text: "We welcome diversity of theological and denominational perspectives." },
      { title: "Integrity", text: "We uphold ethical principles in research and academic publishing." },
    ],
    scopeTitle: "Scope and Domains",
    scopeText:
      "The journal publishes original research articles, research notes, reviews and translations in the following areas:",
    domains: [
      "Systematic and Dogmatic Theology",
      "Biblical Studies (Old and New Testament)",
      "Christian Ethics and Moral Theology",
      "Church History and History of Christianity",
      "Practical and Pastoral Theology",
      "Missiology and Intercultural Theology",
      "Theology and Interreligious Dialogue",
    ],
  },
}

export default function AboutPage() {
  const { locale } = useI18n()
  const c = content[locale]

  return (
    <PageLayout title={c.title} subtitle={c.subtitle}>
      <div className="mx-auto max-w-7xl px-4">
        <div className="max-w-3xl mx-auto">
          {/* History */}
          <section className="mb-16">
            <h2 className="font-serif text-2xl font-bold text-foreground mb-6 flex items-center gap-3">
              <BookOpen className="h-6 w-6 text-accent" />
              {c.historyTitle}
            </h2>
            <div className="flex flex-col gap-4 text-muted-foreground leading-relaxed">
              <p>{c.historyP1}</p>
              <p>{c.historyP2}</p>
              <p>{c.historyP3}</p>
            </div>
          </section>

          {/* Mission & Vision */}
          <section className="mb-16 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div className="bg-muted rounded-lg p-8 border border-border">
              <div className="flex h-12 w-12 items-center justify-center rounded-md bg-primary/10 mb-5">
                <Target className="h-6 w-6 text-primary" />
              </div>
              <h3 className="font-serif text-xl font-semibold text-foreground mb-3">
                {c.missionTitle}
              </h3>
              <p className="text-sm text-muted-foreground leading-relaxed">{c.missionText}</p>
            </div>
            <div className="bg-muted rounded-lg p-8 border border-border">
              <div className="flex h-12 w-12 items-center justify-center rounded-md bg-primary/10 mb-5">
                <Eye className="h-6 w-6 text-primary" />
              </div>
              <h3 className="font-serif text-xl font-semibold text-foreground mb-3">
                {c.visionTitle}
              </h3>
              <p className="text-sm text-muted-foreground leading-relaxed">{c.visionText}</p>
            </div>
          </section>

          {/* Values */}
          <section className="mb-16">
            <h2 className="font-serif text-2xl font-bold text-foreground mb-6">
              {c.valuesTitle}
            </h2>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              {c.values.map((val) => (
                <div key={val.title} className="bg-card rounded-lg p-6 border border-border">
                  <h4 className="font-serif text-base font-semibold text-foreground mb-2">
                    {val.title}
                  </h4>
                  <p className="text-sm text-muted-foreground leading-relaxed">{val.text}</p>
                </div>
              ))}
            </div>
          </section>

          {/* Scope */}
          <section>
            <h2 className="font-serif text-2xl font-bold text-foreground mb-4 flex items-center gap-3">
              <Globe className="h-6 w-6 text-accent" />
              {c.scopeTitle}
            </h2>
            <p className="text-muted-foreground leading-relaxed mb-6">{c.scopeText}</p>
            <ul className="flex flex-col gap-2">
              {c.domains.map((domain) => (
                <li key={domain} className="flex items-start gap-3 text-muted-foreground">
                  <span className="mt-2 h-1.5 w-1.5 rounded-full bg-accent shrink-0" />
                  {domain}
                </li>
              ))}
            </ul>
          </section>
        </div>
      </div>
    </PageLayout>
  )
}
