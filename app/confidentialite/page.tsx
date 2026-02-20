"use client"

import { PageLayout } from "@/components/page-layout"
import { useI18n } from "@/components/i18n-provider"

const content = {
  fr: {
    title: "Politique de confidentialite",
    subtitle: "Comment nous protegeons vos donnees personnelles.",
    sections: [
      {
        heading: "Collecte des donnees",
        text: "Nous collectons uniquement les informations necessaires au fonctionnement de la revue : nom, prenom, adresse email, affiliation institutionnelle lors de la creation de compte ou de la soumission d'un article. Aucune donnee n'est collectee a votre insu.",
      },
      {
        heading: "Utilisation des donnees",
        text: "Vos donnees sont utilisees exclusivement pour la gestion des soumissions, le processus d'evaluation par les pairs, la communication relative aux publications et l'envoi de notifications si vous etes abonne. Nous ne vendons ni ne partageons vos donnees avec des tiers a des fins commerciales.",
      },
      {
        heading: "Conservation des donnees",
        text: "Vos donnees personnelles sont conservees aussi longtemps que votre compte est actif. Vous pouvez demander la suppression de votre compte et de vos donnees a tout moment en nous contactant.",
      },
      {
        heading: "Securite",
        text: "Nous mettons en oeuvre des mesures de securite techniques et organisationnelles pour proteger vos donnees contre tout acces non autorise, modification, divulgation ou destruction.",
      },
      {
        heading: "Cookies",
        text: "Notre site utilise des cookies essentiels au fonctionnement du site (session, preferences de langue). Aucun cookie de pistage ou publicitaire n'est utilise.",
      },
      {
        heading: "Vos droits",
        text: "Vous disposez d'un droit d'acces, de rectification et de suppression de vos donnees personnelles. Pour exercer ces droits, contactez-nous a l'adresse : revue.theologie@upc.ac.cd",
      },
    ],
  },
  en: {
    title: "Privacy Policy",
    subtitle: "How we protect your personal data.",
    sections: [
      {
        heading: "Data Collection",
        text: "We only collect information necessary for the operation of the journal: name, email address, and institutional affiliation when creating an account or submitting an article. No data is collected without your knowledge.",
      },
      {
        heading: "Use of Data",
        text: "Your data is used exclusively for submission management, the peer review process, publication-related communications, and sending notifications if you are subscribed. We do not sell or share your data with third parties for commercial purposes.",
      },
      {
        heading: "Data Retention",
        text: "Your personal data is retained as long as your account is active. You may request deletion of your account and data at any time by contacting us.",
      },
      {
        heading: "Security",
        text: "We implement technical and organizational security measures to protect your data against unauthorized access, modification, disclosure, or destruction.",
      },
      {
        heading: "Cookies",
        text: "Our site uses essential cookies for site functionality (session, language preferences). No tracking or advertising cookies are used.",
      },
      {
        heading: "Your Rights",
        text: "You have the right to access, rectify, and delete your personal data. To exercise these rights, contact us at: revue.theologie@upc.ac.cd",
      },
    ],
  },
}

export default function PrivacyPage() {
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
