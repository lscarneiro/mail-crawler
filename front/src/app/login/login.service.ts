import {Injectable} from '@angular/core';
import {environment} from '../../environments/environment';
import {Observable} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {Login} from '../models/login';
import {TokenResponse} from '../models/token-response';

@Injectable({
  providedIn: 'root'
})
export class LoginService {

  constructor(private http: HttpClient) {
  }

  authenticate(login: Login): Observable<TokenResponse> {
    return this.http.post<TokenResponse>(`${environment.apiUrl}/login`, login);
  }
}
