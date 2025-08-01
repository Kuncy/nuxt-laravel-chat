import type { NavigationGuard } from 'vue-router'
export type MiddlewareKey = "auth" | "guest" | "role-admin" | "role-user" | "verified"
declare module 'nuxt/app' {
  interface PageMeta {
    middleware?: MiddlewareKey | NavigationGuard | Array<MiddlewareKey | NavigationGuard>
  }
}