"use client"

import Link from "next/link"
import { PageLayout } from "@/components/page-layout"
import { useI18n } from "@/components/i18n-provider"
import { Badge } from "@/components/ui/badge"
import { sampleVolumes } from "@/lib/sample-data"
import { BookOpen, Calendar, FileText } from "lucide-react"

const content = {
  fr: {
    title: "Archives",
    subtitle: "Consultez l'ensemble des volumes et numeros publies.",
    articles: "articles",
    publishedOn: "Publie le",
    viewIssue: "Voir le numero",
  },
  en: {
    title: "Archives",
    subtitle: "Browse all published volumes and issues.",
    articles: "articles",
    publishedOn: "Published on",
    viewIssue: "View issue",
  },
}

export default function ArchivesPage() {
  const { locale } = useI18n()
  const c = content[locale]

  return (
    <PageLayout title={c.title} subtitle={c.subtitle}>
      <div className="mx-auto max-w-7xl px-4">
        <div className="max-w-4xl mx-auto">
          <div className="flex flex-col gap-10">
            {sampleVolumes.map((volume) => (
              <section key={volume.year}>
                <div className="flex items-center gap-3 mb-6 pb-3 border-b border-border">
                  <div className="flex h-10 w-10 items-center justify-center rounded-md bg-primary/10">
                    <BookOpen className="h-5 w-5 text-primary" />
                  </div>
                  <div>
                    <h2 className="font-serif text-xl font-bold text-foreground">
                      {locale === "fr" ? volume.description : volume.descriptionEn}
                    </h2>
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  {volume.issues.map((issue) => (
                    <Link
                      key={issue.id}
                      href={`/publications?volume=${volume.number}&issue=${issue.id}`}
                      className="flex flex-col p-6 rounded-lg border border-border bg-card hover:border-accent hover:shadow-sm transition-all group"
                    >
                      <h3 className="font-serif text-lg font-semibold text-foreground group-hover:text-accent transition-colors">
                        {locale === "fr" ? issue.title : issue.titleEn}
                      </h3>
                      <p className="mt-1 text-sm text-muted-foreground">
                        {locale === "fr" ? issue.description : issue.descriptionEn}
                      </p>
                      <div className="mt-4 flex items-center gap-4 text-xs text-muted-foreground">
                        <span className="flex items-center gap-1">
                          <Calendar className="h-3.5 w-3.5" />
                          {new Date(issue.date).toLocaleDateString(locale === "fr" ? "fr-FR" : "en-US", {
                            year: "numeric",
                            month: "long",
                          })}
                        </span>
                        <span className="flex items-center gap-1">
                          <FileText className="h-3.5 w-3.5" />
                          {issue.articleCount} {c.articles}
                        </span>
                      </div>
                    </Link>
                  ))}
                </div>
              </section>
            ))}
          </div>
        </div>
      </div>
    </PageLayout>
  )
}
