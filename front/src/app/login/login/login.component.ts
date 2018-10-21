import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {AccessTokenService} from '../../core/access-token.service';
import {LoginService} from '../login.service';
import {FormBuilder, FormGroup} from '@angular/forms';
import {HttpErrorResponse} from '@angular/common/http';
import {Login} from '../../models/login';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  constructor(private fb: FormBuilder,
              private loginService: LoginService,
              private accessTokenService: AccessTokenService,
              private router: Router) {
  }

  formGroup: FormGroup;

  ngOnInit() {
    this.formGroup = this.fb.group({
      email: [null],
      password: [null],
    });
    const token = this.accessTokenService.get();
    if (!!token) {
      this.router.navigateByUrl('/emails');
    }
  }

  login() {
    if (!this.formGroup.valid) {
      alert('Email e senha obrigatórios');
      return;
    }
    const login = <Login>Object.assign({}, this.formGroup.value);
    this.loginService.authenticate(login).subscribe(data => {
      this.accessTokenService.set(data.token);
      this.router.navigateByUrl('/emails');
    }, err => {
      if (err instanceof HttpErrorResponse) {
        if (err.status === 422) {
          alert('Email e/ou senha inválido(s).');
        } else {
          alert('Ocorreu um erro não esperado, recarregue a página e tente novamente');
        }
      }
    });
  }
}
