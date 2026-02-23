"use client"

import Link from "next/link"
import { PageLayout } from "@/components/page-layout"
import { useI18n } from "@/components/i18n-provider"
import { Button } from "@/components/ui/button"
import { FileUp, CheckCircle, ArrowRight } from "lucide-react"

const content = {
  fr: {
    title: "Instructions aux auteurs",
    subtitle: "Guide complet pour la soumission de manuscrits a la revue.",
    guidelines: [
      {
        title: "Format du manuscrit",
        items: [
          "Les articles doivent etre rediges en francais ou en anglais.",
          "Le manuscrit doit etre presente en format Word (.docx) ou PDF.",
          "Longueur recommandee : entre 5 000 et 10 000 mots, notes et references comprises.",
          "Police : Times New Roman, taille 12, interligne 1,5.",
          "Les marges doivent etre de 2,5 cm de chaque cote.",
        ],
      },
      {
        title: "Structure de l'article",
        items: [
          "Titre de l'article (en francais et en anglais).",
          "Resume de 150 a 250 mots (en francais et en anglais).",
          "Cinq mots-cles (en francais et en anglais).",
          "Introduction, corps de l'article et conclusion.",
          "Notes de bas de page numerotees consecutivement.",
          "Bibliographie en fin d'article selon le style Chicago.",
        ],
      },
      {
        title: "Page de garde",
        items: [
          "Nom complet de l'auteur (ou des co-auteurs).",
          "Affiliation institutionnelle.",
          "Adresse e-mail de correspondance.",
          "Notice biographique de 50 a 100 mots.",
          "Declaration de non-plagiat et d'originalite.",
        ],
      },
      {
        title: "Processus de soumission",
        items: [
          "Envoyez votre manuscrit a revue.theologie@upc.ac.cd.",
          "Confirmation de reception sous 2 semaines.",
          "Evaluation par les pairs en 6 a 8 semaines.",
          "Notification de la decision avec les rapports des evaluateurs.",
          "Revision (si necessaire) sous 4 semaines.",
        ],
      },
    ],
    ctaTitle: "Pret a soumettre votre article ?",
    ctaText: "Envoyez votre manuscrit accompagne de la page de garde a notre adresse e-mail.",
    ctaButton: "Soumettre par e-mail",
    contactLink: "Contactez-nous pour plus d'informations",
  },
  en: {
    title: "Author Guidelines",
    subtitle: "Complete guide for submitting manuscripts to the journal.",
    guidelines: [
      {
        title: "Manuscript Format",
        items: [
          "Articles must be written in French or English.",
          "The manuscript must be submitted in Word (.docx) or PDF format.",
          "Recommended length: between 5,000 and 10,000 words, including notes and references.",
          "Font: Times New Roman, size 12, 1.5 line spacing.",
          "Margins must be 2.5 cm on each side.",
        ],
      },
      {
        title: "Article Structure",
        items: [
          "Article title (in French and English).",
          "Abstract of 150 to 250 words (in French and English).",
          "Five keywords (in French and English).",
          "Introduction, body of the article, and conclusion.",
          "Footnotes numbered consecutively.",
          "Bibliography at the end of the article in Chicago style.",
        ],
      },
      {
        title: "Cover Page",
        items: [
          "Full name of the author (or co-authors).",
          "Institutional affiliation.",
          "Correspondence email address.",
          "Biographical note of 50 to 100 words.",
          "Declaration of non-plagiarism and originality.",
        ],
      },
      {
        title: "Submission Process",
        items: [
          "Send your manuscript to revue.theologie@upc.ac.cd.",
          "Acknowledgment of receipt within 2 weeks.",
          "Peer review in 6 to 8 weeks.",
          "Decision notification with reviewer reports.",
          "Revision (if necessary) within 4 weeks.",
        ],
      },
    ],
    ctaTitle: "Ready to submit your article?",
    ctaText: "Send your manuscript along with the cover page to our email address.",
    ctaButton: "Submit by email",
    contactLink: "Contact us for more information",
  },
}

export default function SubmissionsPage() {
  const { locale } = useI18n()
  const c = content[locale]

  return (
    <PageLayout title={c.title} subtitle={c.subtitle}>
      <div className="mx-auto max-w-7xl px-4">
        <div className="max-w-3xl mx-auto">
          <div className="flex flex-col gap-10">
            {c.guidelines.map((section, index) => (
              <section key={section.title}>
                <h2 className="font-serif text-xl font-bold text-foreground mb-4 flex items-start gap-3">
                  <span className="text-accent font-mono text-sm mt-1 shrink-0">
                    {String(index + 1).padStart(2, "0")}
                  </span>
                  {section.title}
                </h2>
                <ul className="flex flex-col gap-2 pl-9">
                  {section.items.map((item, i) => (
                    <li key={i} className="flex items-start gap-3 text-muted-foreground leading-relaxed">
                      <CheckCircle className="h-4 w-4 text-accent mt-1 shrink-0" />
                      <span>{item}</span>
                    </li>
                  ))}
                </ul>
              </section>
            ))}
          </div>

          {/* CTA */}
          <div className="mt-16 bg-primary rounded-lg p-8 md:p-10 text-center">
            <div className="flex h-14 w-14 items-center justify-center rounded-full bg-primary-foreground/10 mx-auto mb-4">
              <FileUp className="h-7 w-7 text-primary-foreground" />
            </div>
            <h3 className="font-serif text-2xl font-bold text-primary-foreground mb-3">
              {c.ctaTitle}
            </h3>
            <p className="text-primary-foreground/70 mb-6 max-w-md mx-auto leading-relaxed">
              {c.ctaText}
            </p>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-3">
              <Button asChild size="lg" className="bg-accent hover:bg-accent/90 text-accent-foreground">
                <a href="mailto:revue.theologie@upc.ac.cd">
                  {c.ctaButton}
                  <ArrowRight className="ml-2 h-4 w-4" />
                </a>
              </Button>
              <Button
                asChild
                variant="outline"
                size="lg"
                className="border-primary-foreground/30 text-primary-foreground bg-transparent hover:bg-primary-foreground/10 hover:text-primary-foreground"
              >
                <Link href="/contact">{c.contactLink}</Link>
              </Button>
            </div>
          </div>
        </div>
      </div>
    </PageLayout>
  )
}
