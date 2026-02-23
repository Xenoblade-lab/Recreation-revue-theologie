"use client"

import { createContext, useContext, useState, useCallback, type ReactNode } from "react"
import { type Locale, defaultLocale, translations, type TranslationKey } from "@/lib/i18n"

interface I18nContextType {
  locale: Locale
  t: TranslationKey
  toggleLocale: () => void
}

const I18nContext = createContext<I18nContextType | null>(null)

export function I18nProvider({ children }: { children: ReactNode }) {
  const [locale, setLocale] = useState<Locale>(defaultLocale)

  const toggleLocale = useCallback(() => {
    setLocale((prev) => (prev === "fr" ? "en" : "fr"))
  }, [])

  const t = translations[locale]

  return (
    <I18nContext.Provider value={{ locale, t, toggleLocale }}>
      {children}
    </I18nContext.Provider>
  )
}

export function useI18n() {
  const context = useContext(I18nContext)
  if (!context) {
    throw new Error("useI18n must be used within an I18nProvider")
  }
  return context
}
