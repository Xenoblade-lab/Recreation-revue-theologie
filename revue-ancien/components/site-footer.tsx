"use client"

import Link from "next/link"
import { BookOpen, Mail, MapPin } from "lucide-react"
import { useI18n } from "@/components/i18n-provider"

export function SiteFooter() {
  const { t } = useI18n()

  return (
    <footer className="bg-primary text-primary-foreground">
      <div className="mx-auto max-w-7xl px-4 py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
          {/* Brand */}
          <div className="lg:col-span-1">
            <Link href="/" className="flex items-center gap-3 mb-4">
              <div className="flex h-10 w-10 items-center justify-center rounded-sm bg-primary-foreground/10 border border-primary-foreground/20">
                <BookOpen className="h-5 w-5 text-primary-foreground" />
              </div>
              <div>
                <h3 className="font-serif text-base font-bold leading-tight">
                  Revue de Theologie
                </h3>
                <p className="text-xs text-primary-foreground/60">UPC</p>
              </div>
            </Link>
            <p className="text-sm text-primary-foreground/70 leading-relaxed">
              {t.footer.description}
            </p>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="font-serif text-sm font-semibold mb-4 text-primary-foreground/90">
              {t.footer.quickLinks}
            </h4>
            <ul className="flex flex-col gap-2.5">
              <li>
                <Link href="/a-propos" className="text-sm text-primary-foreground/60 hover:text-primary-foreground transition-colors">
                  {t.nav.about}
                </Link>
              </li>
              <li>
                <Link href="/comite" className="text-sm text-primary-foreground/60 hover:text-primary-foreground transition-colors">
                  {t.nav.committee}
                </Link>
              </li>
              <li>
                <Link href="/publications" className="text-sm text-primary-foreground/60 hover:text-primary-foreground transition-colors">
                  {t.nav.publications}
                </Link>
              </li>
              <li>
                <Link href="/archives" className="text-sm text-primary-foreground/60 hover:text-primary-foreground transition-colors">
                  {t.nav.archives}
                </Link>
              </li>
              <li>
                <Link href="/politique-editoriale" className="text-sm text-primary-foreground/60 hover:text-primary-foreground transition-colors">
                  {t.nav.policy}
                </Link>
              </li>
            </ul>
          </div>

          {/* For Authors */}
          <div>
            <h4 className="font-serif text-sm font-semibold mb-4 text-primary-foreground/90">
              {t.footer.forAuthors}
            </h4>
            <ul className="flex flex-col gap-2.5">
              <li>
                <Link href="/soumissions" className="text-sm text-primary-foreground/60 hover:text-primary-foreground transition-colors">
                  {t.footer.authorGuidelines}
                </Link>
              </li>
              <li>
                <Link href="/soumissions" className="text-sm text-primary-foreground/60 hover:text-primary-foreground transition-colors">
                  {t.footer.submitManuscript}
                </Link>
              </li>
              <li>
                <Link href="/faq" className="text-sm text-primary-foreground/60 hover:text-primary-foreground transition-colors">
                  {t.nav.faq}
                </Link>
              </li>
              <li>
                <Link href="/author/subscribe" className="text-sm text-primary-foreground/60 hover:text-primary-foreground transition-colors">
                  {t.footer.subscriptions}
                </Link>
              </li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="font-serif text-sm font-semibold mb-4 text-primary-foreground/90">
              {t.footer.contactUs}
            </h4>
            <ul className="flex flex-col gap-3">
              <li className="flex items-start gap-2">
                <MapPin className="h-4 w-4 mt-0.5 text-primary-foreground/50 shrink-0" />
                <span className="text-sm text-primary-foreground/60">
                  {t.footer.address}
                </span>
              </li>
              <li className="flex items-center gap-2">
                <Mail className="h-4 w-4 text-primary-foreground/50 shrink-0" />
                <a
                  href="mailto:revue.theologie@upc.ac.cd"
                  className="text-sm text-primary-foreground/60 hover:text-primary-foreground transition-colors"
                >
                  revue.theologie@upc.ac.cd
                </a>
              </li>
            </ul>
          </div>
        </div>

        {/* Bottom bar */}
        <div className="mt-12 pt-6 border-t border-primary-foreground/10 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p className="text-xs text-primary-foreground/50">
            &copy; {new Date().getFullYear()} Revue de la Faculte de Theologie &mdash; UPC. {t.footer.rights}
          </p>
          <div className="flex items-center gap-4">
            <Link href="/confidentialite" className="text-xs text-primary-foreground/50 hover:text-primary-foreground/80 transition-colors">
              {t.footer.privacy}
            </Link>
            <Link href="/conditions" className="text-xs text-primary-foreground/50 hover:text-primary-foreground/80 transition-colors">
              {t.footer.terms}
            </Link>
          </div>
        </div>
      </div>
    </footer>
  )
}
