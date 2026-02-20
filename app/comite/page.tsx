"use client"

import { PageLayout } from "@/components/page-layout"
import { useI18n } from "@/components/i18n-provider"
import { Avatar, AvatarFallback } from "@/components/ui/avatar"
import { Badge } from "@/components/ui/badge"

const content = {
  fr: {
    title: "Comite editorial",
    subtitle: "Les membres du comite scientifique et editorial de la revue.",
    editorInChief: "Redacteur en chef",
    associateEditors: "Redacteurs associes",
    scientificCommittee: "Comite scientifique",
    editorialSecretary: "Secretaire de redaction",
    reviewers: "Relecteurs / Evaluateurs externes",
    reviewersNote: "La revue dispose d'un vaste reseau de relecteurs specialises dans les differents domaines de la theologie. Les evaluations sont menees en double aveugle.",
  },
  en: {
    title: "Editorial Board",
    subtitle: "Members of the scientific and editorial committee of the journal.",
    editorInChief: "Editor-in-Chief",
    associateEditors: "Associate Editors",
    scientificCommittee: "Scientific Committee",
    editorialSecretary: "Editorial Secretary",
    reviewers: "Reviewers / External Evaluators",
    reviewersNote: "The journal has an extensive network of reviewers specializing in various fields of theology. Reviews are conducted double-blind.",
  },
}

const editorInChief = {
  name: "Prof. Emmanuel Banywesize",
  affiliation: "Universite Protestante au Congo",
  specialty: "Theologie Systematique",
  specialtyEn: "Systematic Theology",
}

const associateEditors = [
  { name: "Prof. Ruth Mbuyi Kalala", affiliation: "UPC, Kinshasa", specialty: "Etudes Bibliques", specialtyEn: "Biblical Studies" },
  { name: "Dr. Samuel Ndonga", affiliation: "UPC, Kinshasa", specialty: "Ethique Chretienne", specialtyEn: "Christian Ethics" },
]

const editorialSecretary = {
  name: "Dr. Grace Ntumba",
  affiliation: "Universite Protestante au Congo",
  specialty: "Theologie Pratique",
  specialtyEn: "Practical Theology",
}

const scientificCommittee = [
  { name: "Prof. Jean-Marc Ela", affiliation: "Universite de Yaounde", country: "Cameroun" },
  { name: "Prof. Ka Mana", affiliation: "Institut Oecumenique Al Mowafaqa", country: "Maroc" },
  { name: "Prof. Benezet Bujo", affiliation: "Universite de Fribourg", country: "Suisse" },
  { name: "Prof. Tinyiko Maluleke", affiliation: "University of Pretoria", country: "Afrique du Sud" },
  { name: "Prof. Jesse Mugambi", affiliation: "University of Nairobi", country: "Kenya" },
  { name: "Prof. Dietrich Werner", affiliation: "Bread for the World", country: "Allemagne" },
  { name: "Prof. Christina Landman", affiliation: "University of South Africa", country: "Afrique du Sud" },
  { name: "Prof. Andre Kabasele Mukenge", affiliation: "Universite Catholique du Congo", country: "RDC" },
]

function PersonCard({ name, affiliation, specialty, country }: { name: string; affiliation: string; specialty?: string; country?: string }) {
  const initials = name
    .replace(/^(Prof\.|Dr\.)\s*/, "")
    .split(" ")
    .map((n) => n[0])
    .join("")
    .slice(0, 2)
    .toUpperCase()

  return (
    <div className="flex items-start gap-4 p-4 rounded-lg border border-border bg-card hover:shadow-sm transition-shadow">
      <Avatar className="h-12 w-12 bg-primary/10 border border-border">
        <AvatarFallback className="bg-primary/10 text-primary text-sm font-semibold">{initials}</AvatarFallback>
      </Avatar>
      <div className="min-w-0">
        <h4 className="font-serif text-base font-semibold text-foreground leading-tight">{name}</h4>
        <p className="text-sm text-muted-foreground mt-0.5">{affiliation}</p>
        {specialty && (
          <Badge variant="secondary" className="mt-2 text-xs font-normal">{specialty}</Badge>
        )}
        {country && (
          <Badge variant="outline" className="mt-2 text-xs font-normal">{country}</Badge>
        )}
      </div>
    </div>
  )
}

export default function CommitteePage() {
  const { locale } = useI18n()
  const c = content[locale]

  return (
    <PageLayout title={c.title} subtitle={c.subtitle}>
      <div className="mx-auto max-w-7xl px-4">
        <div className="max-w-4xl mx-auto">
          {/* Editor in Chief */}
          <section className="mb-12">
            <h2 className="font-serif text-2xl font-bold text-foreground mb-6 pb-3 border-b border-border">
              {c.editorInChief}
            </h2>
            <PersonCard
              name={editorInChief.name}
              affiliation={editorInChief.affiliation}
              specialty={locale === "fr" ? editorInChief.specialty : editorInChief.specialtyEn}
            />
          </section>

          {/* Associate Editors */}
          <section className="mb-12">
            <h2 className="font-serif text-2xl font-bold text-foreground mb-6 pb-3 border-b border-border">
              {c.associateEditors}
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              {associateEditors.map((editor) => (
                <PersonCard
                  key={editor.name}
                  name={editor.name}
                  affiliation={editor.affiliation}
                  specialty={locale === "fr" ? editor.specialty : editor.specialtyEn}
                />
              ))}
            </div>
          </section>

          {/* Editorial Secretary */}
          <section className="mb-12">
            <h2 className="font-serif text-2xl font-bold text-foreground mb-6 pb-3 border-b border-border">
              {c.editorialSecretary}
            </h2>
            <PersonCard
              name={editorialSecretary.name}
              affiliation={editorialSecretary.affiliation}
              specialty={locale === "fr" ? editorialSecretary.specialty : editorialSecretary.specialtyEn}
            />
          </section>

          {/* Scientific Committee */}
          <section className="mb-12">
            <h2 className="font-serif text-2xl font-bold text-foreground mb-6 pb-3 border-b border-border">
              {c.scientificCommittee}
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              {scientificCommittee.map((member) => (
                <PersonCard
                  key={member.name}
                  name={member.name}
                  affiliation={member.affiliation}
                  country={member.country}
                />
              ))}
            </div>
          </section>

          {/* Reviewers note */}
          <section>
            <h2 className="font-serif text-2xl font-bold text-foreground mb-4 pb-3 border-b border-border">
              {c.reviewers}
            </h2>
            <p className="text-muted-foreground leading-relaxed bg-muted p-6 rounded-lg border border-border">
              {c.reviewersNote}
            </p>
          </section>
        </div>
      </div>
    </PageLayout>
  )
}
