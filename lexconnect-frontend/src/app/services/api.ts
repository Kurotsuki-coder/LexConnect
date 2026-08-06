import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

function getCookie(name: string): string | null {
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  return match ? decodeURIComponent(match[2]) : null;
}

@Injectable({ providedIn: 'root' })
export class Api {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  private ensureCsrfCookie(): Observable<any> {
    return this.http.get(`${this.apiUrl}/sanctum/csrf-cookie`, { withCredentials: true });
  }

  private headers(): HttpHeaders {
    const token = getCookie('XSRF-TOKEN');
    return new HttpHeaders(token ? { 'X-XSRF-TOKEN': token } : {});
  }

  get<T>(path: string): Observable<T> {
    return this.http.get<T>(`${this.apiUrl}${path}`, { withCredentials: true });
  }

  post<T>(path: string, body: any): Observable<T> {
    return new Observable((observer) => {
      this.ensureCsrfCookie().subscribe(() => {
        this.http
          .post<T>(`${this.apiUrl}${path}`, body, { withCredentials: true, headers: this.headers() })
          .subscribe({ next: (v) => { observer.next(v); observer.complete(); }, error: (e) => observer.error(e) });
      });
    });
  }

  postFile<T>(path: string, formData: FormData): Observable<T> {
    return new Observable((observer) => {
      this.ensureCsrfCookie().subscribe(() => {
        this.http
          .post<T>(`${this.apiUrl}${path}`, formData, { withCredentials: true, headers: this.headers() })
          .subscribe({ next: (v) => { observer.next(v); observer.complete(); }, error: (e) => observer.error(e) });
      });
    });
  }

  put<T>(path: string, body: any): Observable<T> {
    return new Observable((observer) => {
      this.ensureCsrfCookie().subscribe(() => {
        this.http
          .put<T>(`${this.apiUrl}${path}`, body, { withCredentials: true, headers: this.headers() })
          .subscribe({ next: (v) => { observer.next(v); observer.complete(); }, error: (e) => observer.error(e) });
      });
    });
  }

  patch<T>(path: string, body: any = {}): Observable<T> {
    return new Observable((observer) => {
      this.ensureCsrfCookie().subscribe(() => {
        this.http
          .patch<T>(`${this.apiUrl}${path}`, body, { withCredentials: true, headers: this.headers() })
          .subscribe({ next: (v) => { observer.next(v); observer.complete(); }, error: (e) => observer.error(e) });
      });
    });
  }

  delete<T>(path: string, body: any = {}): Observable<T> {
    return new Observable((observer) => {
      this.ensureCsrfCookie().subscribe(() => {
        this.http
          .request<T>('DELETE', `${this.apiUrl}${path}`, { body, withCredentials: true, headers: this.headers() })
          .subscribe({ next: (v) => { observer.next(v); observer.complete(); }, error: (e) => observer.error(e) });
      });
    });
  }
}