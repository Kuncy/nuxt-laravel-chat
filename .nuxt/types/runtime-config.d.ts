import { RuntimeConfig as UserRuntimeConfig, PublicRuntimeConfig as UserPublicRuntimeConfig } from 'nuxt/schema'
  interface SharedRuntimeConfig {
   app: {
      buildId: string,

      baseURL: string,

      buildAssetsDir: string,

      cdnURL: string,
   },

   apiLocal: string,

   nitro: {
      envPrefix: string,
   },

   icon: {
      serverKnownCssClasses: Array<any>,
   },

   private: {
      basicAuth: boolean,
   },

   security: {
      strict: boolean,

      headers: {
         crossOriginResourcePolicy: string,

         crossOriginOpenerPolicy: string,

         crossOriginEmbedderPolicy: string,

         contentSecurityPolicy: {
            "base-uri": Array<string>,

            "font-src": Array<string>,

            "form-action": Array<string>,

            "frame-ancestors": Array<string>,

            "img-src": Array<string>,

            "object-src": Array<string>,

            "script-src-attr": Array<string>,

            "style-src": Array<string>,

            "script-src": Array<string>,

            "upgrade-insecure-requests": boolean,
         },

         originAgentCluster: string,

         referrerPolicy: string,

         strictTransportSecurity: {
            maxAge: number,

            includeSubdomains: boolean,
         },

         xContentTypeOptions: string,

         xDNSPrefetchControl: string,

         xDownloadOptions: string,

         xFrameOptions: string,

         xPermittedCrossDomainPolicies: string,

         xXSSProtection: string,

         permissionsPolicy: {
            camera: Array<any>,

            "display-capture": Array<any>,

            fullscreen: Array<any>,

            geolocation: Array<any>,

            microphone: Array<any>,
         },
      },

      requestSizeLimiter: {
         maxRequestSizeInBytes: number,

         maxUploadFileRequestInBytes: number,

         throwError: boolean,
      },

      rateLimiter: {
         tokensPerInterval: number,

         interval: number,

         headers: boolean,

         driver: {
            name: string,
         },

         whiteList: any,

         throwError: boolean,
      },

      xssValidator: {
         methods: Array<string>,

         throwError: boolean,
      },

      corsHandler: {
         origin: string,

         methods: Array<string>,

         preflight: {
            statusCode: number,
         },
      },

      allowedMethodsRestricter: {
         methods: string,

         throwError: boolean,
      },

      hidePoweredBy: boolean,

      enabled: boolean,

      csrf: boolean,

      nonce: boolean,

      removeLoggers: boolean,

      ssg: {
         meta: boolean,

         hashScripts: boolean,

         hashStyles: boolean,

         nitroHeaders: boolean,

         exportToPresets: boolean,
      },

      sri: boolean,
   },
  }
  interface SharedPublicRuntimeConfig {
   authGuard: string,

   apiBase: string,

   apiPrefix: string,

   storageBase: string,

   providers: {
      google: {
         name: string,

         icon: string,

         color: string,

         variant: string,
      },
   },
  }
declare module '@nuxt/schema' {
  interface RuntimeConfig extends UserRuntimeConfig {}
  interface PublicRuntimeConfig extends UserPublicRuntimeConfig {}
}
declare module 'nuxt/schema' {
  interface RuntimeConfig extends SharedRuntimeConfig {}
  interface PublicRuntimeConfig extends SharedPublicRuntimeConfig {}
}
declare module 'vue' {
        interface ComponentCustomProperties {
          $config: UserRuntimeConfig
        }
      }