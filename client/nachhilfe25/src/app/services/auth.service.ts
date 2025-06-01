import {computed, Injectable, signal} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {jwtDecode} from 'jwt-decode';
import {Observable} from 'rxjs';

interface Token {
  exp: number;
  user: {
    id: string;
    role: string;
  }
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private api:string = 'http://nachhilfe25.s2210456033.student.kwmhgb.at/api';
  private loggedIn = signal<boolean>(false);
  private userRole = signal<string | null>(null);
  isLoggedIn = computed(() => this.loggedIn());
  role = computed(() => this.userRole());

  constructor(private http:HttpClient) {
    this.loggedIn.set(this.checkLoginStatus());

    const storedRole = sessionStorage.getItem('role');
    if(storedRole) {
      this.userRole.set(storedRole);
    }
  }

  login(email:string, password:string) {
    return this.http.post(`${this.api}/auth/login`, {email, password});
  }

  logout() {
    // am Server ausloggen
    this.http.post(`${this.api}/auth/logout`, {});
    // Daten aus der Session löschen
    sessionStorage.removeItem('token');
    sessionStorage.removeItem('userId');
    sessionStorage.removeItem('role');
    this.loggedIn.set(false);
  }

  public checkLoginStatus(): boolean {
    if(sessionStorage.getItem('token')) {
      let token: string = <string>sessionStorage.getItem('token');
      const decodedToken = jwtDecode(token) as Token;
      let expirationDate = new Date(0); // von 1970 startet das Datum, sekunden seit da
      expirationDate.setUTCSeconds(decodedToken.exp); // standard eine Stunde ist man eingeloggt

      if(expirationDate > new Date()) { // wenn eine Stunde noch nicht abgelaufen ist
        this.loggedIn.set(true);
        return true;
      } else {
        // Token abgelaufen
        sessionStorage.removeItem('token');
        sessionStorage.removeItem('userId');
        sessionStorage.removeItem('role');
        this.loggedIn.set(false);
        return false;
      }

    } else {
      this.loggedIn.set(false);
      return false;
    }
  }

  public getCurrentUserId(): number {
    return Number.parseInt(<string>sessionStorage.getItem('userId') || '-1');
  }

  getRole(): string | null {
    return this.userRole();
  }

  // speichert JWT Token
  setSessionStorage(access_token:string) {
    const decodedToken = jwtDecode(access_token) as Token; // auf interface casten
    sessionStorage.setItem('token', access_token); // den muss man wieder im http header mitgeben später
    sessionStorage.setItem('userId', decodedToken.user.id); // https sind immer strings
    sessionStorage.setItem('role', decodedToken.user.role);
    this.userRole.set(decodedToken.user.role);
    this.loggedIn.set(true);
  }
}
