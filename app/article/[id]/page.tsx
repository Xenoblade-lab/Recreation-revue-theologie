"use client"

import { use } from "react"
import Link from "next/link"
import { notFound } from "next/navigation"
import { SiteHeader } from "@/components/site-header"
import { SiteFooter } from "@/components/site-footer"
import { useI18n } from "@/components/i18n-provider"
import { Badge } from "@/components/ui/badge"
import { Button } from "@/components/ui/button"
import { sampleArticles, categoryLabels } from "@/lib/sample-data"
import { ArrowLeft, Download, Share2, BookOpen, Calendar, FileText } from "lucide-react"

const content = {
  fr: {
    backTo: "Retour aux publications",
    abstract: "Resume",
    keywords: "Mots-cles",
    downloadPdf: "Telecharger PDF",
    share: "Partager",
    cite: "Citer cet article",
    volume: "Volume",
    issue: "Numero",
    pages: "Pages",
    published: "Publie le",
    affiliation: "Affiliation",
    category: "Domaine",
    doi: "DOI",
    relatedArticles: "Articles connexes",
    readMore: "Lire",
  },
  en: {
    backTo: "Back to publications",
    abstract: "Abstract",
    keywords: "Keywords",
    downloadPdf: "Download PDF",
    share: "Share",
    cite: "Cite this article",
    volume: "Volume",
    issue: "Issue",
    pages: "Pages",
    published: "Published on",
    affiliation: "Affiliation",
    category: "Field",
    doi: "DOI",
    relatedArticles: "Related Articles",
    readMore: "Read",
  },
}

export default function ArticleDetailPage({ params }: { params: Promise<{ id: string }> }) {
  const { id } = use(params)
  const { locale } = useI18n()
  const c = content[locale]

  const article = sampleArticles.find((a) => a.id === parseInt(id))

  if (!article) {
    notFound()
  }

  const relatedArticles = sampleArticles
    .filter((a) => a.category === article.category && a.id !== article.id)
    .slice(0, 3)

  const title = locale === "fr" ? article.titleFr : article.titleEn
  const author = locale === "fr" ? article.authorFr : article.authorEn
  const abstract = locale === "fr" ? article.abstractFr : article.abstractEn
  const categoryLabel = categoryLabels[article.category]?.[locale] || article.category

  return (
    <div className="min-h-screen flex flex-col">
      <SiteHeader />
      <main className="flex-1">
        {/* Article header */}
        <div className="bg-primary py-12 md:py-16">
          <div className="mx-auto max-w-4xl px-4">
            <Link
              href="/publications"
              className="inline-flex items-center gap-1.5 text-sm text-primary-foreground/60 hover:text-primary-foreground transition-colors mb-6"
            >
              <ArrowLeft className="h-4 w-4" />
              {c.backTo}
            </Link>

            <div className="flex items-center gap-2 mb-4 flex-wrap">
              <Badge className="bg-accent text-accent-foreground border-0 text-xs">
                {categoryLabel}
              </Badge>
              <span className="text-xs text-primary-foreground/50">
                {c.volume} {article.volume}, {c.issue} {article.issue}
              </span>
            </div>

            <h1 className="font-serif text-2xl md:text-3xl lg:text-4xl font-bold text-primary-foreground leading-tight text-balance">
              {title}
            </h1>

            <p className="mt-4 text-base md:text-lg text-primary-foreground/80">
              {author}
            </p>
            <p className="mt-1 text-sm text-primary-foreground/50">
              {article.affiliation}
            </p>
          </div>
        </div>

        {/* Article content */}
        <div className="py-12 md:py-16">
          <div className="mx-auto max-w-4xl px-4">
            <div className="flex flex-col lg:flex-row gap-10">
              {/* Main content */}
              <div className="flex-1 min-w-0">
                {/* Abstract */}
                <section className="mb-10">
                  <h2 className="font-serif text-xl font-bold text-foreground mb-4 pb-2 border-b border-border">
                    {c.abstract}
                  </h2>
                  <p className="text-muted-foreground leading-relaxed">
                    {abstract}
                  </p>
                </section>

                {/* Keywords */}
                <section className="mb-10">
                  <h3 className="text-sm font-semibold text-foreground mb-3">
                    {c.keywords}
                  </h3>
                  <div className="flex flex-wrap gap-2">
                    {article.keywords.map((keyword) => (
                      <Badge key={keyword} variant="secondary" className="text-xs font-normal">
                        {keyword}
                      </Badge>
                    ))}
                  </div>
                </section>

                {/* Actions */}
                <div className="flex flex-wrap gap-3 pt-6 border-t border-border">
                  <Button className="bg-accent hover:bg-accent/90 text-accent-foreground">
                    <Download className="mr-2 h-4 w-4" />
                    {c.downloadPdf}
                  </Button>
                  <Button variant="outline">
                    <Share2 className="mr-2 h-4 w-4" />
                    {c.share}
                  </Button>
                </div>

                {/* Citation */}
                <section className="mt-10">
                  <h3 className="text-sm font-semibold text-foreground mb-3">
                    {c.cite}
                  </h3>
                  <div className="bg-muted rounded-lg p-4 text-sm text-muted-foreground font-mono leading-relaxed border border-border">
                    {author} ({new Date(article.date).getFullYear()}). &quot;{title}&quot;.{" "}
                    <em>Revue de la Faculte de Theologie</em>, {article.volume}({article.issue}), pp. {article.pages}.
                    {article.doi && <> DOI: {article.doi}</>}
                  </div>
                </section>
              </div>

              {/* Sidebar */}
              <aside className="lg:w-72 shrink-0">
                <div className="bg-muted rounded-lg p-6 border border-border lg:sticky lg:top-32">
                  <h3 className="font-serif text-base font-semibold text-foreground mb-4">
                    {"Details"}
                  </h3>
                  <dl className="flex flex-col gap-3 text-sm">
                    <div>
                      <dt className="text-muted-foreground">{c.category}</dt>
                      <dd className="font-medium text-foreground">{categoryLabel}</dd>
                    </div>
                    <div>
                      <dt className="text-muted-foreground">{c.volume}</dt>
                      <dd className="font-medium text-foreground">{article.volume}</dd>
                    </div>
                    <div>
                      <dt className="text-muted-foreground">{c.issue}</dt>
                      <dd className="font-medium text-foreground">{article.issue}</dd>
                    </div>
                    <div>
                      <dt className="text-muted-foreground">{c.pages}</dt>
                      <dd className="font-medium text-foreground">{article.pages}</dd>
                    </div>
                    <div>
                      <dt className="text-muted-foreground">{c.published}</dt>
                      <dd className="font-medium text-foreground">
                        {new Date(article.date).toLocaleDateString(locale === "fr" ? "fr-FR" : "en-US", {
                          year: "numeric",
                          month: "long",
                          day: "numeric",
                        })}
                      </dd>
                    </div>
                    {article.doi && (
                      <div>
                        <dt className="text-muted-foreground">{c.doi}</dt>
                        <dd className="font-medium text-accent text-xs font-mono break-all">{article.doi}</dd>
                      </div>
                    )}
                    <div>
                      <dt className="text-muted-foreground">{c.affiliation}</dt>
                      <dd className="font-medium text-foreground">{article.affiliation}</dd>
                    </div>
                  </dl>
                </div>
              </aside>
            </div>

            {/* Related articles */}
            {relatedArticles.length > 0 && (
              <section className="mt-16 pt-10 border-t border-border">
                <h2 className="font-serif text-2xl font-bold text-foreground mb-6">
                  {c.relatedArticles}
                </h2>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                  {relatedArticles.map((related) => (
                    <Link
                      key={related.id}
                      href={`/article/${related.id}`}
                      className="p-5 rounded-lg border border-border bg-card hover:border-accent hover:shadow-sm transition-all group"
                    >
                      <Badge variant="secondary" className="text-xs font-normal mb-2">
                        {categoryLabels[related.category]?.[locale]}
                      </Badge>
                      <h3 className="font-serif text-sm font-semibold text-foreground group-hover:text-accent transition-colors leading-snug line-clamp-3">
                        {locale === "fr" ? related.titleFr : related.titleEn}
                      </h3>
                      <p className="mt-2 text-xs text-muted-foreground">
                        {locale === "fr" ? related.authorFr : related.authorEn}
                      </p>
                    </Link>
                  ))}
                </div>
              </section>
            )}
          </div>
        </div>
      </main>
      <SiteFooter />
    </div>
  )
}
