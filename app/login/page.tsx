"use client"

import Link from "next/link"
import { BookOpen, ArrowLeft } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { useI18n } from "@/components/i18n-provider"

const content = {
  fr: {
    title: "Connexion",
    subtitle: "Accedez a votre espace personnel",
    email: "Adresse email",
    emailPlaceholder: "votre.email@exemple.com",
    password: "Mot de passe",
    passwordPlaceholder: "Votre mot de passe",
    login: "Se connecter",
    forgotPassword: "Mot de passe oublie ?",
    noAccount: "Pas encore de compte ?",
    register: "Creer un compte",
    back: "Retour a l'accueil",
  },
  en: {
    title: "Login",
    subtitle: "Access your personal space",
    email: "Email address",
    emailPlaceholder: "your.email@example.com",
    password: "Password",
    passwordPlaceholder: "Your password",
    login: "Sign in",
    forgotPassword: "Forgot password?",
    noAccount: "Don't have an account?",
    register: "Create an account",
    back: "Back to home",
  },
}

export default function LoginPage() {
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

      {/* Login card */}
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
              <div className="flex flex-col gap-2">
                <Label htmlFor="email">{c.email}</Label>
                <Input id="email" type="email" placeholder={c.emailPlaceholder} required />
              </div>
              <div className="flex flex-col gap-2">
                <div className="flex items-center justify-between">
                  <Label htmlFor="password">{c.password}</Label>
                  <button type="button" className="text-xs text-accent hover:text-accent/80 transition-colors">
                    {c.forgotPassword}
                  </button>
                </div>
                <Input id="password" type="password" placeholder={c.passwordPlaceholder} required />
              </div>
              <Button type="submit" className="w-full bg-primary hover:bg-primary/90 text-primary-foreground">
                {c.login}
              </Button>
            </form>
          </div>

          {/* Register link */}
          <p className="mt-6 text-center text-sm text-muted-foreground">
            {c.noAccount}{" "}
            <Link href="/register" className="text-accent hover:text-accent/80 font-medium transition-colors">
              {c.register}
            </Link>
          </p>
        </div>
      </div>
    </div>
  )
}
