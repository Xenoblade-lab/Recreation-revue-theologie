"use client"

import { PageLayout } from "@/components/page-layout"
import { useI18n } from "@/components/i18n-provider"

const content = {
  fr: {
    title: "Politique editoriale",
    subtitle: "Procedures d'evaluation, ethique et droits d'auteur.",
    sections: [
      {
        title: "Processus d'evaluation par les pairs",
        paragraphs: [
          "Tous les articles soumis a la Revue de la Faculte de Theologie font l'objet d'une evaluation en double aveugle (double-blind peer review). Les identites des auteurs et des evaluateurs sont mutuellement masquees afin de garantir l'impartialite du processus.",
          "Chaque manuscrit est evalue par au moins deux experts du domaine concerne. Les evaluateurs examinent l'originalite, la methodologie, la contribution scientifique et la conformite ethique de l'article.",
        ],
      },
      {
        title: "Criteres d'acceptation",
        paragraphs: [
          "Les articles sont evalues selon les criteres suivants : originalite de la recherche, rigueur methodologique, contribution au champ d'etude, qualite de la redaction, pertinence des references bibliographiques et conformite aux normes editoriales de la revue.",
        ],
      },
      {
        title: "Delais d'evaluation",
        paragraphs: [
          "Verification editoriale initiale : 2 semaines apres la soumission. Evaluation par les pairs : 6 a 8 semaines. Revision par l'auteur (si requise) : 4 semaines. Decision finale et notification : 2 semaines apres reception des evaluations.",
        ],
      },
      {
        title: "Politique anti-plagiat",
        paragraphs: [
          "Tous les manuscrits soumis sont verifies a l'aide d'un outil de detection de plagiat. Les manuscrits presentant un taux de similarite superieur au seuil acceptable seront rejetes ou renvoyes a l'auteur pour correction.",
          "La revue prend tres au serieux toute forme de fraude scientifique, y compris la fabrication de donnees, la falsification et le plagiat.",
        ],
      },
      {
        title: "Conflit d'interets",
        paragraphs: [
          "Les evaluateurs sont tenus de declarer tout conflit d'interets potentiel avec les auteurs ou le sujet de l'article. En cas de conflit, un evaluateur de remplacement sera designe.",
        ],
      },
      {
        title: "Droits d'auteur et licence",
        paragraphs: [
          "Les articles publies dans la revue sont diffuses sous licence Creative Commons Attribution - Pas d'Utilisation Commerciale (CC BY-NC). Les auteurs conservent leurs droits d'auteur tout en accordant a la revue le droit de premiere publication.",
          "Les auteurs sont responsables de l'obtention des autorisations necessaires pour tout materiel protege par le droit d'auteur inclus dans leur manuscrit.",
        ],
      },
      {
        title: "Retraction et correction",
        paragraphs: [
          "En cas d'erreur significative decouverte apres publication, un erratum ou une retraction sera publie. La revue suit les directives du Committee on Publication Ethics (COPE) pour le traitement des problemes post-publication.",
        ],
      },
    ],
  },
  en: {
    title: "Editorial Policy",
    subtitle: "Review procedures, ethics and copyright.",
    sections: [
      {
        title: "Peer Review Process",
        paragraphs: [
          "All articles submitted to the Journal of the Faculty of Theology undergo double-blind peer review. The identities of both authors and reviewers are mutually concealed to ensure impartiality.",
          "Each manuscript is reviewed by at least two experts in the relevant field. Reviewers assess the originality, methodology, scientific contribution, and ethical compliance of the article.",
        ],
      },
      {
        title: "Acceptance Criteria",
        paragraphs: [
          "Articles are evaluated according to the following criteria: research originality, methodological rigor, contribution to the field of study, quality of writing, relevance of bibliographic references, and compliance with the journal's editorial standards.",
        ],
      },
      {
        title: "Review Timeline",
        paragraphs: [
          "Initial editorial check: 2 weeks after submission. Peer review: 6 to 8 weeks. Author revision (if required): 4 weeks. Final decision and notification: 2 weeks after receipt of reviews.",
        ],
      },
      {
        title: "Anti-Plagiarism Policy",
        paragraphs: [
          "All submitted manuscripts are checked using a plagiarism detection tool. Manuscripts with similarity rates above the acceptable threshold will be rejected or returned to the author for correction.",
          "The journal takes all forms of scientific fraud very seriously, including data fabrication, falsification, and plagiarism.",
        ],
      },
      {
        title: "Conflict of Interest",
        paragraphs: [
          "Reviewers are required to declare any potential conflict of interest with the authors or the subject of the article. In case of conflict, a replacement reviewer will be appointed.",
        ],
      },
      {
        title: "Copyright and License",
        paragraphs: [
          "Articles published in the journal are distributed under the Creative Commons Attribution - NonCommercial (CC BY-NC) license. Authors retain their copyright while granting the journal the right of first publication.",
          "Authors are responsible for obtaining necessary permissions for any copyrighted material included in their manuscript.",
        ],
      },
      {
        title: "Retraction and Correction",
        paragraphs: [
          "If a significant error is discovered after publication, an erratum or retraction will be published. The journal follows the guidelines of the Committee on Publication Ethics (COPE) for handling post-publication issues.",
        ],
      },
    ],
  },
}

export default function PolicyPage() {
  const { locale } = useI18n()
  const c = content[locale]

  return (
    <PageLayout title={c.title} subtitle={c.subtitle}>
      <div className="mx-auto max-w-7xl px-4">
        <div className="max-w-3xl mx-auto">
          <div className="flex flex-col gap-12">
            {c.sections.map((section, index) => (
              <section key={section.title}>
                <h2 className="font-serif text-xl font-bold text-foreground mb-4 flex items-start gap-3">
                  <span className="text-accent font-mono text-sm mt-1 shrink-0">
                    {String(index + 1).padStart(2, "0")}
                  </span>
                  {section.title}
                </h2>
                <div className="flex flex-col gap-3 pl-9">
                  {section.paragraphs.map((p, i) => (
                    <p key={i} className="text-muted-foreground leading-relaxed">
                      {p}
                    </p>
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
