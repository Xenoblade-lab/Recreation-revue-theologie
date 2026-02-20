"use client"

import Link from "next/link"
import { BookOpen, ArrowLeft } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { useI18n } from "@/components/i18n-provider"

const content = {
  fr: {
    title: "Creer un compte",
    subtitle: "Rejoignez la communaute de la revue",
    firstName: "Prenom",
    lastName: "Nom",
    email: "Adresse email",
    emailPlaceholder: "votre.email@exemple.com",
    affiliation: "Affiliation institutionnelle",
    affiliationPlaceholder: "Universite, institut...",
    password: "Mot de passe",
    passwordPlaceholder: "Minimum 8 caracteres",
    confirmPassword: "Confirmer le mot de passe",
    register: "Creer mon compte",
    hasAccount: "Deja un compte ?",
    login: "Se connecter",
    back: "Retour a l'accueil",
  },
  en: {
    title: "Create an Account",
    subtitle: "Join the journal community",
    firstName: "First name",
    lastName: "Last name",
    email: "Email address",
    emailPlaceholder: "your.email@example.com",
    affiliation: "Institutional affiliation",
    affiliationPlaceholder: "University, institute...",
    password: "Password",
    passwordPlaceholder: "Minimum 8 characters",
    confirmPassword: "Confirm password",
    register: "Create my account",
    hasAccount: "Already have an account?",
    login: "Sign in",
    back: "Back to home",
  },
}

export default function RegisterPage() {
  const { locale } = useI18n()
  const c = content[locale]

  return (
    <div className="min-h-screen flex flex-col bg-muted">
      {/* Back link */}
      <div className="mx-auto w-full max-w-7xl px-4 pt-6">
        <Link
          href="/"
          className="inline-flex items-center gap-1.5 text-sm text-muted-foreground hover:text-foreground transition-colors"
        >
          <ArrowLeft className="h-4 w-4" />
          {c.back}
        </Link>
      </div>

      {/* Register card */}
      <div className="flex-1 flex items-center justify-center px-4 py-12">
        <div className="w-full max-w-md">
          {/* Logo */}
          <div className="flex flex-col items-center mb-8">
            <div className="flex h-14 w-14 items-center justify-center rounded-md bg-primary mb-4">
              <BookOpen className="h-7 w-7 text-primary-foreground" />
            </div>
            <h1 className="font-serif text-2xl font-bold text-foreground">{c.title}</h1>
            <p className="mt-1 text-sm text-muted-foreground">{c.subtitle}</p>
          </div>

          {/* Form */}
          <div className="bg-card rounded-lg border border-border p-8">
            <form
              onSubmit={(e) => {
                e.preventDefault()
              }}
              className="flex flex-col gap-5"
            >
              <div className="grid grid-cols-2 gap-4">
                <div className="flex flex-col gap-2">
                  <Label htmlFor="firstName">{c.firstName}</Label>
                  <Input id="firstName" type="text" required />
                </div>
                <div className="flex flex-col gap-2">
                  <Label htmlFor="lastName">{c.lastName}</Label>
                  <Input id="lastName" type="text" required />
                </div>
              </div>
              <div className="flex flex-col gap-2">
                <Label htmlFor="email">{c.email}</Label>
                <Input id="email" type="email" placeholder={c.emailPlaceholder} required />
              </div>
              <div className="flex flex-col gap-2">
                <Label htmlFor="affiliation">{c.affiliation}</Label>
                <Input id="affiliation" type="text" placeholder={c.affiliationPlaceholder} />
              </div>
              <div className="flex flex-col gap-2">
                <Label htmlFor="password">{c.password}</Label>
                <Input id="password" type="password" placeholder={c.passwordPlaceholder} required />
              </div>
              <div className="flex flex-col gap-2">
                <Label htmlFor="confirmPassword">{c.confirmPassword}</Label>
                <Input id="confirmPassword" type="password" required />
              </div>
              <Button type="submit" className="w-full bg-primary hover:bg-primary/90 text-primary-foreground">
                {c.register}
              </Button>
            </form>
          </div>

          {/* Login link */}
          <p className="mt-6 text-center text-sm text-muted-foreground">
            {c.hasAccount}{" "}
            <Link href="/login" className="text-accent hover:text-accent/80 font-medium transition-colors">
              {c.login}
            </Link>
          </p>
        </div>
      </div>
    </div>
  )
}
