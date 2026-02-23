"use client"

import { useState } from "react"
import { PageLayout } from "@/components/page-layout"
import { useI18n } from "@/components/i18n-provider"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Textarea } from "@/components/ui/textarea"
import { MapPin, Mail, Phone, Clock, Send } from "lucide-react"

const content = {
  fr: {
    title: "Contact",
    subtitle: "Contactez-nous pour toute question relative a la revue.",
    formTitle: "Envoyez-nous un message",
    name: "Nom complet",
    namePlaceholder: "Votre nom",
    email: "Adresse e-mail",
    emailPlaceholder: "votre@email.com",
    subject: "Objet",
    subjectPlaceholder: "Objet de votre message",
    message: "Message",
    messagePlaceholder: "Ecrivez votre message ici...",
    send: "Envoyer le message",
    sent: "Message envoye !",
    sentText: "Nous avons bien recu votre message et vous repondrons dans les plus brefs delais.",
    infoTitle: "Informations de contact",
    address: "Universite Protestante au Congo, Faculte de Theologie, Avenue de la Liberation, Kinshasa, Republique Democratique du Congo",
    phone: "+243 81 234 5678",
    emailAddress: "revue.theologie@upc.ac.cd",
    hours: "Lundi - Vendredi : 8h00 - 16h00",
    addressLabel: "Adresse",
    phoneLabel: "Telephone",
    emailLabel: "E-mail",
    hoursLabel: "Horaires",
  },
  en: {
    title: "Contact",
    subtitle: "Get in touch with us for any questions about the journal.",
    formTitle: "Send us a message",
    name: "Full name",
    namePlaceholder: "Your name",
    email: "Email address",
    emailPlaceholder: "your@email.com",
    subject: "Subject",
    subjectPlaceholder: "Subject of your message",
    message: "Message",
    messagePlaceholder: "Write your message here...",
    send: "Send message",
    sent: "Message sent!",
    sentText: "We have received your message and will respond as soon as possible.",
    infoTitle: "Contact Information",
    address: "Protestant University in Congo, Faculty of Theology, Avenue de la Liberation, Kinshasa, Democratic Republic of Congo",
    phone: "+243 81 234 5678",
    emailAddress: "revue.theologie@upc.ac.cd",
    hours: "Monday - Friday: 8:00 AM - 4:00 PM",
    addressLabel: "Address",
    phoneLabel: "Phone",
    emailLabel: "Email",
    hoursLabel: "Hours",
  },
}

export default function ContactPage() {
  const { locale } = useI18n()
  const c = content[locale]
  const [submitted, setSubmitted] = useState(false)

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault()
    setSubmitted(true)
  }

  return (
    <PageLayout title={c.title} subtitle={c.subtitle}>
      <div className="mx-auto max-w-7xl px-4">
        <div className="grid grid-cols-1 lg:grid-cols-5 gap-12">
          {/* Contact Form */}
          <div className="lg:col-span-3">
            <h2 className="font-serif text-2xl font-bold text-foreground mb-6">
              {c.formTitle}
            </h2>

            {submitted ? (
              <div className="rounded-lg border border-border bg-muted p-8 text-center">
                <div className="flex h-12 w-12 items-center justify-center rounded-full bg-accent/10 mx-auto mb-4">
                  <Send className="h-6 w-6 text-accent" />
                </div>
                <h3 className="font-serif text-xl font-semibold text-foreground mb-2">
                  {c.sent}
                </h3>
                <p className="text-muted-foreground leading-relaxed">{c.sentText}</p>
              </div>
            ) : (
              <form onSubmit={handleSubmit} className="flex flex-col gap-5">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                  <div className="flex flex-col gap-2">
                    <Label htmlFor="name">{c.name}</Label>
                    <Input id="name" placeholder={c.namePlaceholder} required />
                  </div>
                  <div className="flex flex-col gap-2">
                    <Label htmlFor="email">{c.email}</Label>
                    <Input id="email" type="email" placeholder={c.emailPlaceholder} required />
                  </div>
                </div>
                <div className="flex flex-col gap-2">
                  <Label htmlFor="subject">{c.subject}</Label>
                  <Input id="subject" placeholder={c.subjectPlaceholder} required />
                </div>
                <div className="flex flex-col gap-2">
                  <Label htmlFor="message">{c.message}</Label>
                  <Textarea
                    id="message"
                    placeholder={c.messagePlaceholder}
                    rows={6}
                    required
                  />
                </div>
                <Button type="submit" size="lg" className="self-start bg-accent hover:bg-accent/90 text-accent-foreground">
                  <Send className="mr-2 h-4 w-4" />
                  {c.send}
                </Button>
              </form>
            )}
          </div>

          {/* Contact Info */}
          <div className="lg:col-span-2">
            <h2 className="font-serif text-2xl font-bold text-foreground mb-6">
              {c.infoTitle}
            </h2>
            <div className="flex flex-col gap-6">
              <div className="flex items-start gap-4 p-5 rounded-lg border border-border bg-card">
                <div className="flex h-10 w-10 items-center justify-center rounded-md bg-primary/10 shrink-0">
                  <MapPin className="h-5 w-5 text-primary" />
                </div>
                <div>
                  <h4 className="font-serif text-sm font-semibold text-foreground mb-1">{c.addressLabel}</h4>
                  <p className="text-sm text-muted-foreground leading-relaxed">{c.address}</p>
                </div>
              </div>

              <div className="flex items-start gap-4 p-5 rounded-lg border border-border bg-card">
                <div className="flex h-10 w-10 items-center justify-center rounded-md bg-primary/10 shrink-0">
                  <Phone className="h-5 w-5 text-primary" />
                </div>
                <div>
                  <h4 className="font-serif text-sm font-semibold text-foreground mb-1">{c.phoneLabel}</h4>
                  <a href={`tel:${c.phone.replace(/\s/g, "")}`} className="text-sm text-muted-foreground hover:text-accent transition-colors">
                    {c.phone}
                  </a>
                </div>
              </div>

              <div className="flex items-start gap-4 p-5 rounded-lg border border-border bg-card">
                <div className="flex h-10 w-10 items-center justify-center rounded-md bg-primary/10 shrink-0">
                  <Mail className="h-5 w-5 text-primary" />
                </div>
                <div>
                  <h4 className="font-serif text-sm font-semibold text-foreground mb-1">{c.emailLabel}</h4>
                  <a href={`mailto:${c.emailAddress}`} className="text-sm text-muted-foreground hover:text-accent transition-colors">
                    {c.emailAddress}
                  </a>
                </div>
              </div>

              <div className="flex items-start gap-4 p-5 rounded-lg border border-border bg-card">
                <div className="flex h-10 w-10 items-center justify-center rounded-md bg-primary/10 shrink-0">
                  <Clock className="h-5 w-5 text-primary" />
                </div>
                <div>
                  <h4 className="font-serif text-sm font-semibold text-foreground mb-1">{c.hoursLabel}</h4>
                  <p className="text-sm text-muted-foreground">{c.hours}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </PageLayout>
  )
}
