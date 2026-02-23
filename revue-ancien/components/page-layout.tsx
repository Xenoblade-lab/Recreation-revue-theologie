"use client"

import { SiteHeader } from "@/components/site-header"
import { SiteFooter } from "@/components/site-footer"

export function PageLayout({
  children,
  title,
  subtitle,
}: {
  children: React.ReactNode
  title: string
  subtitle?: string
}) {
  return (
    <div className="min-h-screen flex flex-col">
      <SiteHeader />
      <main className="flex-1">
        {/* Page header */}
        <div className="bg-primary py-16 md:py-20">
          <div className="mx-auto max-w-7xl px-4">
            <h1 className="font-serif text-3xl md:text-4xl lg:text-5xl font-bold text-primary-foreground text-balance">
              {title}
            </h1>
            {subtitle && (
              <p className="mt-4 text-base md:text-lg text-primary-foreground/70 max-w-2xl">
                {subtitle}
              </p>
            )}
          </div>
        </div>
        {/* Page content */}
        <div className="py-12 md:py-16">
          {children}
        </div>
      </main>
      <SiteFooter />
    </div>
  )
}
