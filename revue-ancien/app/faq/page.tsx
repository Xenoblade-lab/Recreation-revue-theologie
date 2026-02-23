"use client"

import { useState } from "react"
import { PageLayout } from "@/components/page-layout"
import { useI18n } from "@/components/i18n-provider"
import { ChevronDown } from "lucide-react"

const content = {
  fr: {
    title: "Foire aux questions",
    subtitle: "Retrouvez les reponses aux questions les plus frequentes concernant la revue.",
    categories: [
      {
        name: "Soumission d'articles",
        questions: [
          {
            q: "Comment soumettre un article a la revue ?",
            a: "Les articles doivent etre soumis via notre plateforme en ligne apres creation d'un compte auteur. Vous pouvez acceder au formulaire de soumission depuis la page Soumissions. Assurez-vous de suivre les instructions aux auteurs avant de soumettre.",
          },
          {
            q: "Quels sont les formats acceptes pour les manuscrits ?",
            a: "Les manuscrits doivent etre soumis au format Word (.doc ou .docx) ou LaTeX. Les figures et tableaux doivent etre inclus dans le document principal ainsi que soumis separement en haute resolution (300 dpi minimum).",
          },
          {
            q: "Y a-t-il des frais de soumission ou de publication ?",
            a: "La soumission est gratuite. Aucun frais de traitement d'article (APC) n'est facture aux auteurs. La revue est financee par l'Universite Protestante au Congo.",
          },
          {
            q: "Quelle est la longueur maximale d'un article ?",
            a: "Les articles de recherche doivent compter entre 6 000 et 10 000 mots, notes et references comprises. Les notes de recherche sont limitees a 4 000 mots. Les comptes-rendus ne doivent pas depasser 2 000 mots.",
          },
        ],
      },
      {
        name: "Processus d'evaluation",
        questions: [
          {
            q: "Quel est le processus d'evaluation des articles ?",
            a: "Tous les articles soumis font l'objet d'une evaluation en double aveugle par au moins deux evaluateurs specialises. Le processus complet prend generalement entre 8 et 12 semaines.",
          },
          {
            q: "Quels sont les criteres d'evaluation ?",
            a: "Les articles sont evalues selon leur originalite, la rigueur methodologique, la contribution au champ d'etude, la qualite de la redaction et la pertinence des references bibliographiques.",
          },
          {
            q: "Puis-je suivre l'etat de ma soumission ?",
            a: "Oui, apres connexion a votre espace auteur, vous pouvez consulter le statut de votre soumission en temps reel. Vous recevrez egalement des notifications par e-mail a chaque etape du processus.",
          },
        ],
      },
      {
        name: "Publication et acces",
        questions: [
          {
            q: "Les articles publies sont-ils en acces libre ?",
            a: "Oui, la revue pratique l'acces ouvert (Open Access). Tous les articles publies sont disponibles gratuitement sous licence Creative Commons CC BY-NC.",
          },
          {
            q: "Quelle est la periodicite de la revue ?",
            a: "La revue publie deux numeros par an, generalement en janvier et en juin. Des numeros speciaux thematiques peuvent etre publies en supplement.",
          },
          {
            q: "Comment obtenir un ISSN ou un DOI pour mon article ?",
            a: "La revue dispose d'un ISSN propre et attribue un DOI unique a chaque article publie. Ces identifiants sont generes automatiquement lors de la publication.",
          },
        ],
      },
      {
        name: "Questions generales",
        questions: [
          {
            q: "En quelles langues puis-je soumettre un article ?",
            a: "La revue accepte les articles en francais et en anglais. Un resume dans les deux langues est obligatoire pour tout article soumis.",
          },
          {
            q: "Comment contacter la redaction ?",
            a: "Vous pouvez nous contacter par e-mail a revue.theologie@upc.ac.cd ou via le formulaire de contact disponible sur notre site. Le secretariat de redaction est disponible du lundi au vendredi, de 8h00 a 16h00.",
          },
          {
            q: "Puis-je proposer un numero thematique ?",
            a: "Oui, les propositions de numeros thematiques sont les bienvenues. Envoyez une description detaillee du theme propose, accompagnee d'un calendrier previsionnel, a l'adresse du redacteur en chef.",
          },
        ],
      },
    ],
  },
  en: {
    title: "Frequently Asked Questions",
    subtitle: "Find answers to the most common questions about the journal.",
    categories: [
      {
        name: "Article Submission",
        questions: [
          {
            q: "How do I submit an article to the journal?",
            a: "Articles must be submitted through our online platform after creating an author account. You can access the submission form from the Submissions page. Please follow the author guidelines before submitting.",
          },
          {
            q: "What formats are accepted for manuscripts?",
            a: "Manuscripts must be submitted in Word format (.doc or .docx) or LaTeX. Figures and tables should be included in the main document and also submitted separately in high resolution (minimum 300 dpi).",
          },
          {
            q: "Are there any submission or publication fees?",
            a: "Submission is free. No article processing charges (APC) are billed to authors. The journal is funded by the Protestant University in Congo.",
          },
          {
            q: "What is the maximum article length?",
            a: "Research articles should be between 6,000 and 10,000 words, including notes and references. Research notes are limited to 4,000 words. Book reviews should not exceed 2,000 words.",
          },
        ],
      },
      {
        name: "Review Process",
        questions: [
          {
            q: "What is the article review process?",
            a: "All submitted articles undergo double-blind peer review by at least two specialized reviewers. The entire process typically takes between 8 and 12 weeks.",
          },
          {
            q: "What are the evaluation criteria?",
            a: "Articles are evaluated based on originality, methodological rigor, contribution to the field, quality of writing, and relevance of bibliographic references.",
          },
          {
            q: "Can I track my submission status?",
            a: "Yes, after logging into your author dashboard, you can check the status of your submission in real time. You will also receive email notifications at each stage of the process.",
          },
        ],
      },
      {
        name: "Publication and Access",
        questions: [
          {
            q: "Are published articles open access?",
            a: "Yes, the journal practices Open Access. All published articles are freely available under a Creative Commons CC BY-NC license.",
          },
          {
            q: "How often is the journal published?",
            a: "The journal publishes two issues per year, typically in January and June. Special thematic issues may be published as supplements.",
          },
          {
            q: "How do I obtain an ISSN or DOI for my article?",
            a: "The journal has its own ISSN and assigns a unique DOI to each published article. These identifiers are generated automatically upon publication.",
          },
        ],
      },
      {
        name: "General Questions",
        questions: [
          {
            q: "In which languages can I submit an article?",
            a: "The journal accepts articles in French and English. An abstract in both languages is required for all submitted articles.",
          },
          {
            q: "How can I contact the editorial office?",
            a: "You can reach us by email at revue.theologie@upc.ac.cd or through the contact form on our website. The editorial office is available Monday through Friday, from 8:00 AM to 4:00 PM.",
          },
          {
            q: "Can I propose a thematic issue?",
            a: "Yes, proposals for thematic issues are welcome. Please send a detailed description of the proposed theme, along with a provisional timeline, to the editor-in-chief's email address.",
          },
        ],
      },
    ],
  },
}

function FaqItem({ question, answer }: { question: string; answer: string }) {
  const [open, setOpen] = useState(false)

  return (
    <div className="border-b border-border last:border-b-0">
      <button
        onClick={() => setOpen(!open)}
        className="flex items-start justify-between w-full py-5 text-left gap-4"
        aria-expanded={open}
      >
        <span className="font-serif text-base font-semibold text-foreground leading-snug">
          {question}
        </span>
        <ChevronDown
          className={`h-5 w-5 text-muted-foreground shrink-0 mt-0.5 transition-transform ${
            open ? "rotate-180" : ""
          }`}
        />
      </button>
      {open && (
        <div className="pb-5 -mt-1">
          <p className="text-sm text-muted-foreground leading-relaxed">{answer}</p>
        </div>
      )}
    </div>
  )
}

export default function FaqPage() {
  const { locale } = useI18n()
  const c = content[locale]

  return (
    <PageLayout title={c.title} subtitle={c.subtitle}>
      <div className="mx-auto max-w-7xl px-4">
        <div className="max-w-3xl mx-auto">
          <div className="flex flex-col gap-10">
            {c.categories.map((category) => (
              <section key={category.name}>
                <h2 className="font-serif text-xl font-bold text-foreground mb-2 pb-3 border-b-2 border-accent/30">
                  {category.name}
                </h2>
                <div>
                  {category.questions.map((faq) => (
                    <FaqItem key={faq.q} question={faq.q} answer={faq.a} />
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
