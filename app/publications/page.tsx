"use client"

import Link from "next/link"
import { useState } from "react"
import { PageLayout } from "@/components/page-layout"
import { useI18n } from "@/components/i18n-provider"
import { Badge } from "@/components/ui/badge"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { sampleArticles, categoryLabels } from "@/lib/sample-data"
import { Search, FileText, Download, Filter } from "lucide-react"

const content = {
  fr: {
    title: "Publications",
    subtitle: "Parcourez l'ensemble des articles publies dans notre revue.",
    search: "Rechercher par titre, auteur ou mot-cle...",
    filterAll: "Tous",
    noResults: "Aucun article ne correspond a votre recherche.",
    pages: "Pages",
    readMore: "Lire",
    volume: "Vol.",
    issue: "No.",
  },
  en: {
    title: "Publications",
    subtitle: "Browse all articles published in our journal.",
    search: "Search by title, author or keyword...",
    filterAll: "All",
    noResults: "No articles match your search.",
    pages: "Pages",
    readMore: "Read",
    volume: "Vol.",
    issue: "No.",
  },
}

export default function PublicationsPage() {
  const { locale } = useI18n()
  const c = content[locale]
  const [search, setSearch] = useState("")
  const [activeCategory, setActiveCategory] = useState("all")

  const filteredArticles = sampleArticles.filter((article) => {
    const title = locale === "fr" ? article.titleFr : article.titleEn
    const author = locale === "fr" ? article.authorFr : article.authorEn
    const matchesSearch =
      search === "" ||
      title.toLowerCase().includes(search.toLowerCase()) ||
      author.toLowerCase().includes(search.toLowerCase()) ||
      article.keywords.some((k) => k.toLowerCase().includes(search.toLowerCase()))
    const matchesCategory = activeCategory === "all" || article.category === activeCategory
    return matchesSearch && matchesCategory
  })

  const categories = ["all", ...Object.keys(categoryLabels)]

  return (
    <PageLayout title={c.title} subtitle={c.subtitle}>
      <div className="mx-auto max-w-7xl px-4">
        {/* Search and Filters */}
        <div className="mb-10 flex flex-col gap-4">
          <div className="relative max-w-xl">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
            <Input
              placeholder={c.search}
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="pl-10"
            />
          </div>
          <div className="flex items-center gap-2 flex-wrap">
            <Filter className="h-4 w-4 text-muted-foreground shrink-0" />
            {categories.map((cat) => (
              <button
                key={cat}
                onClick={() => setActiveCategory(cat)}
                className={`px-3 py-1.5 text-xs font-medium rounded-md transition-colors ${
                  activeCategory === cat
                    ? "bg-primary text-primary-foreground"
                    : "bg-muted text-muted-foreground hover:text-foreground"
                }`}
              >
                {cat === "all" ? c.filterAll : categoryLabels[cat]?.[locale] || cat}
              </button>
            ))}
          </div>
        </div>

        {/* Articles list */}
        {filteredArticles.length === 0 ? (
          <p className="text-muted-foreground text-center py-12">{c.noResults}</p>
        ) : (
          <div className="flex flex-col gap-0 divide-y divide-border">
            {filteredArticles.map((article) => (
              <article key={article.id} className="py-6 first:pt-0 group">
                <div className="flex flex-col md:flex-row md:items-start gap-4">
                  <div className="flex-1 min-w-0">
                    <div className="flex items-center gap-2 mb-2 flex-wrap">
                      <Badge variant="secondary" className="text-xs font-normal">
                        {categoryLabels[article.category]?.[locale]}
                      </Badge>
                      <span className="text-xs text-muted-foreground">
                        {c.volume} {article.volume}, {c.issue} {article.issue}
                      </span>
                      <span className="text-xs text-muted-foreground">
                        {c.pages}: {article.pages}
                      </span>
                      {article.doi && (
                        <span className="text-xs text-accent font-mono">
                          DOI: {article.doi}
                        </span>
                      )}
                    </div>
                    <Link href={`/article/${article.id}`}>
                      <h3 className="font-serif text-lg font-semibold text-foreground group-hover:text-accent transition-colors leading-snug">
                        {locale === "fr" ? article.titleFr : article.titleEn}
                      </h3>
                    </Link>
                    <p className="mt-1 text-sm text-muted-foreground">
                      {locale === "fr" ? article.authorFr : article.authorEn}
                      <span className="text-muted-foreground/60">{' '}&mdash; {article.affiliation}</span>
                    </p>
                    <p className="mt-2 text-sm text-muted-foreground/80 leading-relaxed line-clamp-2">
                      {locale === "fr" ? article.abstractFr : article.abstractEn}
                    </p>
                  </div>
                  <div className="flex items-center gap-2 shrink-0">
                    <Button variant="ghost" size="sm" asChild className="text-muted-foreground hover:text-foreground">
                      <Link href={`/article/${article.id}`}>
                        <FileText className="mr-1 h-4 w-4" />
                        {c.readMore}
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
        )}
      </div>
    </PageLayout>
  )
}
