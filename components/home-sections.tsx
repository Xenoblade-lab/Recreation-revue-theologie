"use client"

import Image from "next/image"
import Link from "next/link"
import { ArrowRight, BookOpen, Users, ShieldCheck, FileText, Download } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { useI18n } from "@/components/i18n-provider"

// Sample data for latest articles
const sampleArticles = [
  {
    id: 1,
    titleFr: "La pneumatologie dans la tradition reformee africaine : perspectives contemporaines",
    titleEn: "Pneumatology in the African Reformed Tradition: Contemporary Perspectives",
    authorFr: "Prof. Jean-Baptiste Muamba",
    authorEn: "Prof. Jean-Baptiste Muamba",
    category: "systematic-theology",
    volume: 28,
    issue: 1,
    pages: "15-42",
    date: "2025-09-15",
  },
  {
    id: 2,
    titleFr: "Hermeneutique contextuelle et lecture africaine du Nouveau Testament",
    titleEn: "Contextual Hermeneutics and African Reading of the New Testament",
    authorFr: "Dr. Marie-Claire Lunda",
    authorEn: "Dr. Marie-Claire Lunda",
    category: "biblical-studies",
    volume: 28,
    issue: 1,
    pages: "43-68",
    date: "2025-09-15",
  },
  {
    id: 3,
    titleFr: "Ethique chretienne et justice sociale en Republique Democratique du Congo",
    titleEn: "Christian Ethics and Social Justice in the Democratic Republic of Congo",
    authorFr: "Dr. Patrick Kasongo",
    authorEn: "Dr. Patrick Kasongo",
    category: "christian-ethics",
    volume: 28,
    issue: 1,
    pages: "69-94",
    date: "2025-09-15",
  },
]

const categoryMap: Record<string, { fr: string; en: string }> = {
  "systematic-theology": { fr: "Theologie Systematique", en: "Systematic Theology" },
  "biblical-studies": { fr: "Etudes Bibliques", en: "Biblical Studies" },
  "christian-ethics": { fr: "Ethique Chretienne", en: "Christian Ethics" },
  "church-history": { fr: "Histoire de l'Eglise", en: "Church History" },
  "practical-theology": { fr: "Theologie Pratique", en: "Practical Theology" },
}

export function HeroSection() {
  const { t } = useI18n()

  return (
    <section className="relative overflow-hidden">
      {/* Hero image background */}
      <div className="absolute inset-0">
        <Image
          src="/images/hero-theology.jpg"
          alt=""
          fill
          className="object-cover"
          priority
        />
        <div className="absolute inset-0 bg-primary/85" />
      </div>

      <div className="relative mx-auto max-w-7xl px-4 py-24 md:py-32 lg:py-40">
        <div className="max-w-3xl">
          <Badge className="mb-6 bg-accent text-accent-foreground border-0 font-medium">
            {t.hero.latestIssue}: Volume 28, No. 1 &mdash; 2025
          </Badge>
          <h1 className="font-serif text-4xl md:text-5xl lg:text-6xl font-bold text-primary-foreground leading-tight text-balance">
            {t.hero.title}
          </h1>
          <p className="mt-3 font-serif text-xl md:text-2xl text-primary-foreground/80">
            {t.hero.subtitle}
          </p>
          <p className="mt-6 text-base md:text-lg text-primary-foreground/70 leading-relaxed max-w-2xl">
            {t.hero.description}
          </p>
          <div className="mt-8 flex flex-wrap gap-3">
            <Button asChild size="lg" className="bg-accent hover:bg-accent/90 text-accent-foreground font-medium">
              <Link href="/publications">
                {t.hero.browseArchives}
                <ArrowRight className="ml-2 h-4 w-4" />
              </Link>
            </Button>
            <Button
              asChild
              variant="outline"
              size="lg"
              className="border-primary-foreground/30 text-primary-foreground bg-transparent hover:bg-primary-foreground/10 hover:text-primary-foreground"
            >
              <Link href="/soumissions">{t.hero.submitArticle}</Link>
            </Button>
          </div>
        </div>
      </div>
    </section>
  )
}

export function LatestArticlesSection() {
  const { t, locale } = useI18n()

  return (
    <section className="py-16 md:py-24 bg-background">
      <div className="mx-auto max-w-7xl px-4">
        <div className="flex items-end justify-between mb-10">
          <div>
            <h2 className="font-serif text-3xl md:text-4xl font-bold text-foreground">
              {t.sections.latestArticles}
            </h2>
            <p className="mt-2 text-muted-foreground">
              Volume 28, {t.sections.issue} 1 &mdash; 2025
            </p>
          </div>
          <Link
            href="/publications"
            className="hidden sm:flex items-center gap-1 text-sm font-medium text-accent hover:text-accent/80 transition-colors"
          >
            {t.sections.viewAll}
            <ArrowRight className="h-4 w-4" />
          </Link>
        </div>

        <div className="flex flex-col gap-0 divide-y divide-border">
          {sampleArticles.map((article) => (
            <article key={article.id} className="py-6 first:pt-0 last:pb-0 group">
              <div className="flex flex-col md:flex-row md:items-start gap-4">
                <div className="flex-1 min-w-0">
                  <div className="flex items-center gap-2 mb-2">
                    <Badge variant="secondary" className="text-xs font-normal">
                      {categoryMap[article.category]?.[locale] || article.category}
                    </Badge>
                    <span className="text-xs text-muted-foreground">
                      {t.sections.pages}: {article.pages}
                    </span>
                  </div>
                  <Link href={`/article/${article.id}`} className="block">
                    <h3 className="font-serif text-lg md:text-xl font-semibold text-foreground group-hover:text-accent transition-colors leading-snug">
                      {locale === "fr" ? article.titleFr : article.titleEn}
                    </h3>
                  </Link>
                  <p className="mt-1.5 text-sm text-muted-foreground">
                    {locale === "fr" ? article.authorFr : article.authorEn}
                  </p>
                </div>
                <div className="flex items-center gap-2 shrink-0">
                  <Button variant="ghost" size="sm" asChild className="text-muted-foreground hover:text-foreground">
                    <Link href={`/article/${article.id}`}>
                      <FileText className="mr-1 h-4 w-4" />
                      {t.sections.readMore}
                    </Link>
                  </Button>
                  <Button variant="ghost" size="sm" className="text-muted-foreground hover:text-foreground">
                    <Download className="mr-1 h-4 w-4" />
                    PDF
                  </Button>
                </div>
              </div>
            </article>
          ))}
        </div>

        <div className="mt-8 sm:hidden">
          <Link
            href="/publications"
            className="flex items-center gap-1 text-sm font-medium text-accent hover:text-accent/80 transition-colors"
          >
            {t.sections.viewAll}
            <ArrowRight className="h-4 w-4" />
          </Link>
        </div>
      </div>
    </section>
  )
}

export function AboutSection() {
  const { t } = useI18n()

  const features = [
    {
      icon: BookOpen,
      title: t.sections.mission,
      description: t.sections.missionText,
    },
    {
      icon: Users,
      title: t.sections.peerReview,
      description: t.sections.peerReviewText,
    },
    {
      icon: ShieldCheck,
      title: t.sections.openAccess,
      description: t.sections.openAccessText,
    },
  ]

  return (
    <section className="py-16 md:py-24 bg-muted">
      <div className="mx-auto max-w-7xl px-4">
        <div className="text-center max-w-2xl mx-auto mb-12">
          <h2 className="font-serif text-3xl md:text-4xl font-bold text-foreground">
            {t.sections.aboutReview}
          </h2>
          <p className="mt-4 text-muted-foreground leading-relaxed">
            {t.sections.aboutDescription}
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {features.map((feature) => (
            <div
              key={feature.title}
              className="bg-card rounded-lg p-8 border border-border hover:shadow-md transition-shadow"
            >
              <div className="flex h-12 w-12 items-center justify-center rounded-md bg-primary/10 mb-5">
                <feature.icon className="h-6 w-6 text-primary" />
              </div>
              <h3 className="font-serif text-lg font-semibold text-foreground mb-3">
                {feature.title}
              </h3>
              <p className="text-sm text-muted-foreground leading-relaxed">
                {feature.description}
              </p>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}

export function CategoriesSection() {
  const { t } = useI18n()

  const categories = [
    { name: t.sections.systematicTheology, count: 45 },
    { name: t.sections.biblicalStudies, count: 38 },
    { name: t.sections.christianEthics, count: 27 },
    { name: t.sections.churchHistory, count: 32 },
    { name: t.sections.practicalTheology, count: 21 },
  ]

  return (
    <section className="py-16 md:py-24 bg-background">
      <div className="mx-auto max-w-7xl px-4">
        <h2 className="font-serif text-3xl md:text-4xl font-bold text-foreground mb-10 text-center">
          {t.sections.categories}
        </h2>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
          {categories.map((cat) => (
            <Link
              key={cat.name}
              href="/publications"
              className="flex flex-col items-center justify-center py-8 px-4 rounded-lg border border-border bg-card hover:border-accent hover:shadow-sm transition-all text-center group"
            >
              <span className="font-serif text-base font-semibold text-foreground group-hover:text-accent transition-colors">
                {cat.name}
              </span>
              <span className="mt-1 text-sm text-muted-foreground">
                {cat.count} articles
              </span>
            </Link>
          ))}
        </div>
      </div>
    </section>
  )
}

export function CallForPapersSection() {
  const { t } = useI18n()

  return (
    <section className="py-16 md:py-24 bg-primary">
      <div className="mx-auto max-w-7xl px-4 text-center">
        <h2 className="font-serif text-3xl md:text-4xl font-bold text-primary-foreground mb-4">
          {t.sections.callForPapers}
        </h2>
        <p className="text-primary-foreground/70 max-w-xl mx-auto leading-relaxed mb-8">
          {t.sections.callForPapersText}
        </p>
        <div className="flex flex-wrap justify-center gap-3">
          <Button
            asChild
            size="lg"
            className="bg-accent hover:bg-accent/90 text-accent-foreground font-medium"
          >
            <Link href="/soumissions">
              {t.sections.learnMore}
              <ArrowRight className="ml-2 h-4 w-4" />
            </Link>
          </Button>
          <Button
            asChild
            variant="outline"
            size="lg"
            className="border-primary-foreground/30 text-primary-foreground bg-transparent hover:bg-primary-foreground/10 hover:text-primary-foreground"
          >
            <Link href="/contact">{t.footer.contactUs}</Link>
          </Button>
        </div>
      </div>
    </section>
  )
}
