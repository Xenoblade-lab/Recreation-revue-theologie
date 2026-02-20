"use client"

import { PageLayout } from "@/components/page-layout"
import { useI18n } from "@/components/i18n-provider"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { BookOpen, Mail, CheckCircle2 } from "lucide-react"

const content = {
  fr: {
    title: "Abonnements",
    subtitle: "Restez informe des nouvelles publications et des appels a contributions.",
    emailTitle: "Abonnement par email",
    emailDesc: "Recevez une notification par email a chaque nouvelle publication de la revue.",
    emailLabel: "Adresse email",
    emailPlaceholder: "votre.email@exemple.com",
    subscribe: "S'abonner",
    benefits: "Avantages de l'abonnement",
    benefitsList: [
      "Notification a chaque nouveau numero publie",
      "Acces prioritaire aux appels a contributions",
      "Resume des articles en avant-premiere",
      "Invitations aux colloques et conferences de la Faculte",
    ],
    institutional: "Abonnement institutionnel",
    institutionalDesc:
      "Les bibliotheques et institutions peuvent souscrire un abonnement pour recevoir les exemplaires imprimes de la revue. Pour plus d'informations, veuillez nous contacter a l'adresse suivante :",
  },
  en: {
    title: "Subscriptions",
    subtitle: "Stay informed about new publications and calls for papers.",
    emailTitle: "Email subscription",
    emailDesc: "Receive an email notification with each new journal publication.",
    emailLabel: "Email address",
    emailPlaceholder: "your.email@example.com",
    subscribe: "Subscribe",
    benefits: "Subscription benefits",
    benefitsList: [
      "Notification for each new published issue",
      "Priority access to calls for papers",
      "Preview of article summaries",
      "Invitations to Faculty colloquia and conferences",
    ],
    institutional: "Institutional subscription",
    institutionalDesc:
      "Libraries and institutions can subscribe to receive printed copies of the journal. For more information, please contact us at:",
  },
}

export default function SubscribePage() {
  const { locale } = useI18n()
  const c = content[locale]

  return (
    <PageLayout title={c.title} subtitle={c.subtitle}>
      <div className="mx-auto max-w-7xl px-4">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
          {/* Email subscription form */}
          <div>
            <div className="bg-card rounded-lg border border-border p-8">
              <div className="flex items-center gap-3 mb-4">
                <div className="flex h-10 w-10 items-center justify-center rounded-md bg-primary/10">
                  <Mail className="h-5 w-5 text-primary" />
                </div>
                <h2 className="font-serif text-xl font-semibold text-foreground">{c.emailTitle}</h2>
              </div>
              <p className="text-sm text-muted-foreground mb-6 leading-relaxed">{c.emailDesc}</p>
              <form
                onSubmit={(e) => {
                  e.preventDefault()
                }}
                className="flex flex-col gap-4"
              >
                <div className="flex flex-col gap-2">
                  <Label htmlFor="sub-email">{c.emailLabel}</Label>
                  <Input id="sub-email" type="email" placeholder={c.emailPlaceholder} required />
                </div>
                <Button type="submit" className="w-full bg-primary hover:bg-primary/90 text-primary-foreground">
                  {c.subscribe}
                </Button>
              </form>
            </div>

            {/* Institutional */}
            <div className="mt-8 bg-card rounded-lg border border-border p-8">
              <div className="flex items-center gap-3 mb-4">
                <div className="flex h-10 w-10 items-center justify-center rounded-md bg-primary/10">
                  <BookOpen className="h-5 w-5 text-primary" />
                </div>
                <h2 className="font-serif text-xl font-semibold text-foreground">{c.institutional}</h2>
              </div>
              <p className="text-sm text-muted-foreground leading-relaxed">
                {c.institutionalDesc}
              </p>
              <p className="mt-3 text-sm font-medium text-accent">revue.theologie@upc.ac.cd</p>
            </div>
          </div>

          {/* Benefits */}
          <div>
            <h2 className="font-serif text-2xl font-bold text-foreground mb-6">{c.benefits}</h2>
            <ul className="flex flex-col gap-4">
              {c.benefitsList.map((benefit, i) => (
                <li key={i} className="flex items-start gap-3">
                  <CheckCircle2 className="h-5 w-5 text-accent shrink-0 mt-0.5" />
                  <span className="text-foreground leading-relaxed">{benefit}</span>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </div>
    </PageLayout>
  )
}
