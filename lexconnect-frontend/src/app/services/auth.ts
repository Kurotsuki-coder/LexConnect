import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, tap } from 'rxjs';
import { environment } from '../../environments/environment';
import { Utilisateur } from '../models/utilisateur.model';

function getCookie(name: string): string | null {
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  return match ? decodeURIComponent(match[2]) : null;
}

@Injectable({ providedIn: 'root' })
export class Auth {
  private apiUrl = environment.apiUrl;
  currentUser: Utilisateur | null = null;

  constructor(private http: HttpClient) {}

  private getCsrfCookie(): Observable<any> {
    return this.http.get(`${this.apiUrl}/sanctum/csrf-cookie`, { withCredentials: true });
  }

  private authHeaders(): HttpHeaders {
    const token = getCookie('XSRF-TOKEN');
    return new HttpHeaders(token ? { 'X-XSRF-TOKEN': token } : {});
  }

  register(role: 'citoyen' | 'avocat', data: any): Observable<{ user: Utilisateur }> {
    return new Observable((observer) => {
      this.getCsrfCookie().subscribe(() => {
        this.http
          .post<{ user: Utilisateur }>(`${this.apiUrl}/api/register/${role}`, data, {
            withCredentials: true,
            headers: this.authHeaders(),
          })
          .subscribe({
            next: (res) => { this.currentUser = res.user; observer.next(res); observer.complete(); },
            error: (err) => observer.error(err),
          });
      });
    });
  }

  login(email: string, password: string): Observable<{ user: Utilisateur }> {
    return new Observable((observer) => {
      this.getCsrfCookie().subscribe(() => {
        this.http
          .post<{ user: Utilisateur }>(`${this.apiUrl}/api/login`, { email, password }, {
            withCredentials: true,
            headers: this.authHeaders(),
          })
          .subscribe({
            next: (res) => { this.currentUser = res.user; observer.next(res); observer.complete(); },
            error: (err) => observer.error(err),
          });
      });
    });
  }

  logout(): Observable<any> {
    return this.http
      .post(`${this.apiUrl}/api/logout`, {}, { withCredentials: true, headers: this.authHeaders() })
      .pipe(tap(() => (this.currentUser = null)));
  }

  fetchUser(): Observable<Utilisateur> {
    return this.http
      .get<Utilisateur>(`${this.apiUrl}/api/user`, { withCredentials: true })
      .pipe(tap((user) => (this.currentUser = user)));
  }
}