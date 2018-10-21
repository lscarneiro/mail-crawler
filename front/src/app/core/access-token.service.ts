import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class AccessTokenService {

  constructor() { }

  get() {
    return localStorage.getItem('access_token');
  }

  set(accessControl: string) {
    localStorage.setItem('access_token', accessControl);
  }

  clear() {
    localStorage.removeItem('access_token');
    localStorage.clear();
  }
}
