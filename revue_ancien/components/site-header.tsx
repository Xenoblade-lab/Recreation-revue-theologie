"use client"

import Link from "next/link"
import { useState } from "react"
import { Menu, X, Globe, BookOpen } from "lucide-react"
import { Button } from "@/components/ui/button"
import { useI18n } from "@/components/i18n-provider"

export function SiteHeader() {
  const { t, toggleLocale } = useI18n()
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false)

  const navLinks = [
    { href: "/", label: t.nav.home },
    { href: "/a-propos", label: t.nav.about },
    { href: "/comite", label: t.nav.committee },
    { href: "/publications", label: t.nav.publications },
    { href: "/archives", label: t.nav.archives },
    { href: "/soumissions", label: t.nav.submissions },
    { href: "/contact", label: t.nav.contact },
  ]

  return (
    <header className="sticky top-0 z-50 bg-card/95 backdrop-blur-sm border-b border-border">
      {/* Top bar */}
      <div className="bg-primary">
        <div className="mx-auto max-w-7xl px-4 flex items-center justify-between py-1.5 text-sm text-primary-foreground">
          <span className="hidden md:inline">
            {'Universite Protestante au Congo (UPC) - Faculte de Theologie'}
          </span>
          <span className="md:hidden text-xs">{'UPC - Faculte de Theologie'}</span>
          <div className="flex items-center gap-3">
            <button
              onClick={toggleLocale}
              className="flex items-center gap-1 text-primary-foreground/80 hover:text-primary-foreground transition-colors text-xs font-medium"
              aria-label="Switch language"
            >
              <Globe className="h-3.5 w-3.5" />
              {t.nav.language}
            </button>
            <Link
              href="/login"
              className="text-primary-foreground/80 hover:text-primary-foreground transition-colors text-xs font-medium"
            >
              {t.nav.login}
            </Link>
          </div>
        </div>
      </div>

      {/* Main navigation */}
      <div className="mx-auto max-w-7xl px-4">
        <div className="flex items-center justify-between py-4">
          {/* Logo */}
          <Link href="/" className="flex items-center gap-3 shrink-0">
            <div className="flex h-11 w-11 items-center justify-center rounded-sm bg-primary">
              <BookOpen className="h-6 w-6 text-primary-foreground" />
            </div>
            <div className="hidden sm:block">
              <h1 className="font-serif text-lg font-bold leading-tight text-foreground">
                Revue de Theologie
              </h1>
              <p className="text-xs text-muted-foreground leading-tight">UPC</p>
            </div>
          </Link>

          {/* Desktop nav */}
          <nav className="hidden lg:flex items-center gap-1" aria-label="Main navigation">
            {navLinks.map((link) => (
              <Link
                key={link.href}
                href={link.href}
                className="px-3 py-2 text-sm font-medium text-muted-foreground hover:text-foreground transition-colors rounded-md hover:bg-muted"
              >
                {link.label}
              </Link>
            ))}
          </nav>

          {/* Mobile menu button */}
          <Button
            variant="ghost"
            size="icon"
            className="lg:hidden"
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            aria-label="Toggle menu"
          >
            {mobileMenuOpen ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
          </Button>
        </div>
      </div>

      {/* Mobile menu */}
      {mobileMenuOpen && (
        <div className="lg:hidden border-t border-border bg-card">
          <nav className="mx-auto max-w-7xl px-4 py-4 flex flex-col gap-1" aria-label="Mobile navigation">
            {navLinks.map((link) => (
              <Link
                key={link.href}
                href={link.href}
                className="px-3 py-2.5 text-sm font-medium text-muted-foreground hover:text-foreground hover:bg-muted rounded-md transition-colors"
                onClick={() => setMobileMenuOpen(false)}
              >
                {link.label}
              </Link>
            ))}
          </nav>
        </div>
      )}
    </header>
  )
}
