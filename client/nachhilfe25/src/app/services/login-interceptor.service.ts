import {inject, Injectable} from '@angular/core';
import {HttpErrorResponse, HttpEvent, HttpHandler, HttpInterceptor, HttpRequest} from '@angular/common/http';
import {Observable, tap} from 'rxjs';

@Injectable()
export class LoginInterceptorService implements HttpInterceptor{
  constructor() {}

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    return next.handle(req).pipe(tap((event: HttpEvent<any>) => {
      }, (err: any) => {
        if(err instanceof HttpErrorResponse) {
          if(err.status === 401) {
            console.log('incorrect credentials');
          }
        }
      }));
  }
}


