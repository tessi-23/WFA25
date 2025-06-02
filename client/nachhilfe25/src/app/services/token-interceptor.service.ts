import { Injectable } from '@angular/core';
import {HttpEvent, HttpHandler, HttpInterceptor, HttpRequest} from '@angular/common/http';
import {Observable} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class TokenInterceptorService implements HttpInterceptor{

  constructor() { }

  // für den Auth Token
  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    req = req.clone({ // JWT Token clonen, da HTTP-Requests nicht veränderbar sind
      setHeaders: { // Authorization-Header setzen
        Authorization: `Bearer ${sessionStorage.getItem('token')}` // mit Token aus sessionStorage holen
      }
    });
    return next.handle(req); // veränderten Request weitergeben mit Token im Header
  }
}
