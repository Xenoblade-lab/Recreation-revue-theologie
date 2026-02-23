"use client"

import { SiteHeader } from "@/components/site-header"
import { SiteFooter } from "@/components/site-footer"
import {
  HeroSection,
  LatestArticlesSection,
  AboutSection,
  CategoriesSection,
  CallForPapersSection,
} from "@/components/home-sections"

export default function HomePage() {
  return (
    <div className="min-h-screen flex flex-col">
      <SiteHeader />
      <main className="flex-1">
        <HeroSection />
        <LatestArticlesSection />
        <AboutSection />
        <CategoriesSection />
        <CallForPapersSection />
      </main>
      <SiteFooter />
    </div>
  )
}
