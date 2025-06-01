import {ApplicationConfig, LOCALE_ID, provideZoneChangeDetection} from '@angular/core';
import { provideRouter } from '@angular/router';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';
import { providePrimeNG } from 'primeng/config';
import Aura from '@primeng/themes/aura';


import { routes } from './app.routes';
import {HTTP_INTERCEPTORS, provideHttpClient, withFetch, withInterceptorsFromDi} from '@angular/common/http';
import {TokenInterceptorService} from './services/token-interceptor.service';
import {LoginInterceptorService} from './services/login-interceptor.service';

export const appConfig: ApplicationConfig = {
  providers: [
    provideZoneChangeDetection({
      eventCoalescing: true
    }),
    provideRouter(routes),
    provideHttpClient(withFetch(), withInterceptorsFromDi()),
    {provide:LOCALE_ID,useValue:'de'},
    {
      provide:HTTP_INTERCEPTORS,
      useClass:TokenInterceptorService,
      multi:true
    },
    {
      provide:HTTP_INTERCEPTORS,
      useClass:LoginInterceptorService,
      multi:true
    },
    provideAnimationsAsync(),
    providePrimeNG({
      theme: {
        preset: Aura,
        options: {
          cssLayer: {
            name: 'primeng',
            order: 'theme, base, primeng'
          }
        }
      }
    })
  ]
};
