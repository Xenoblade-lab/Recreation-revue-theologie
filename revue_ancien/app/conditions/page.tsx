"use client"

import { PageLayout } from "@/components/page-layout"
import { useI18n } from "@/components/i18n-provider"

const content = {
  fr: {
    title: "Conditions d'utilisation",
    subtitle: "Conditions regissant l'utilisation de la plateforme de la revue.",
    sections: [
      {
        heading: "Acceptation des conditions",
        text: "En accedant a ce site, vous acceptez les presentes conditions d'utilisation. Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser ce site.",
      },
      {
        heading: "Propriete intellectuelle",
        text: "Tous les articles publies dans la Revue de la Faculte de Theologie sont la propriete intellectuelle de leurs auteurs respectifs. Les articles sont publies sous licence Creative Commons Attribution (CC BY 4.0), sauf indication contraire. Toute reproduction doit mentionner la source originale.",
      },
      {
        heading: "Soumission d'articles",
        text: "En soumettant un article, l'auteur certifie que le travail est original, qu'il n'a pas ete publie precedemment et qu'il n'est pas soumis simultanement a une autre publication. L'auteur garantit qu'il dispose de tous les droits necessaires sur le contenu soumis.",
      },
      {
        heading: "Processus d'evaluation",
        text: "Les articles soumis sont evalues en double aveugle par des pairs. Le comite editorial se reserve le droit d'accepter, de demander des revisions ou de refuser tout manuscrit soumis. Les decisions du comite editorial sont finales.",
      },
      {
        heading: "Utilisation du site",
        text: "Vous vous engagez a utiliser ce site de maniere licite et a ne pas tenter d'acceder de maniere non autorisee aux systemes informatiques de la revue. Toute utilisation abusive peut entrainer la suspension de votre compte.",
      },
      {
        heading: "Limitation de responsabilite",
        text: "La revue s'efforce de fournir des informations exactes et a jour. Cependant, nous ne garantissons pas l'exactitude, l'exhaustivite ou la pertinence des informations publiees. La revue ne saurait etre tenue responsable des dommages resultant de l'utilisation de ce site.",
      },
      {
        heading: "Modification des conditions",
        text: "Nous nous reservons le droit de modifier ces conditions a tout moment. Les modifications prennent effet des leur publication sur le site. Nous vous encourageons a consulter regulierement cette page.",
      },
    ],
  },
  en: {
    title: "Terms of Use",
    subtitle: "Terms governing the use of the journal platform.",
    sections: [
      {
        heading: "Acceptance of Terms",
        text: "By accessing this site, you agree to these terms of use. If you do not accept these terms, please do not use this site.",
      },
      {
        heading: "Intellectual Property",
        text: "All articles published in the Journal of the Faculty of Theology are the intellectual property of their respective authors. Articles are published under a Creative Commons Attribution (CC BY 4.0) license, unless otherwise stated. Any reproduction must cite the original source.",
      },
      {
        heading: "Article Submission",
        text: "By submitting an article, the author certifies that the work is original, has not been previously published, and is not simultaneously submitted to another publication. The author warrants that they hold all necessary rights to the submitted content.",
      },
      {
        heading: "Review Process",
        text: "Submitted articles undergo double-blind peer review. The editorial board reserves the right to accept, request revisions, or reject any submitted manuscript. Editorial board decisions are final.",
      },
      {
        heading: "Site Usage",
        text: "You agree to use this site lawfully and not to attempt unauthorized access to the journal's computer systems. Any misuse may result in suspension of your account.",
      },
      {
        heading: "Limitation of Liability",
        text: "The journal strives to provide accurate and up-to-date information. However, we do not guarantee the accuracy, completeness, or relevance of published information. The journal shall not be held liable for damages resulting from the use of this site.",
      },
      {
        heading: "Modification of Terms",
        text: "We reserve the right to modify these terms at any time. Changes take effect upon publication on the site. We encourage you to review this page regularly.",
      },
    ],
  },
}

export default function TermsPage() {
  const { locale } = useI18n()
  const c = content[locale]

  return (
    <PageLayout title={c.title} subtitle={c.subtitle}>
      <div className="mx-auto max-w-3xl px-4">
        <div className="flex flex-col gap-10">
          {c.sections.map((section, i) => (
            <section key={i}>
              <h2 className="font-serif text-xl font-semibold text-foreground mb-3">{section.heading}</h2>
              <p className="text-muted-foreground leading-relaxed">{section.text}</p>
            </section>
          ))}
        </div>
        <p className="mt-12 text-sm text-muted-foreground border-t border-border pt-6">
          {locale === "fr"
            ? "Derniere mise a jour : Janvier 2025"
            : "Last updated: January 2025"}
        </p>
      </div>
    </PageLayout>
  )
}
