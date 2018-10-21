import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {environment} from '../../environments/environment';
import {AccessTokenService} from '../core/access-token.service';

@Injectable({
  providedIn: 'root'
})
export class EmailService {

  constructor(private http: HttpClient,
              private accessTokenService: AccessTokenService) {
  }

  getEmails(): Observable<string[]> {
    const token = this.accessTokenService.get();
    return this.http.get<string[]>(`${environment.apiUrl}/emails`, {
      headers: {
        authorization: `Bearer ${token}`
      }
    });
  }
}
