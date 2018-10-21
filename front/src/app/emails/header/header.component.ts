import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {AccessTokenService} from '../../core/access-token.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {

  constructor(private accessTokenService: AccessTokenService,
              private router: Router) {
  }

  ngOnInit() {
  }

  logout() {
    this.accessTokenService.clear();
    this.router.navigateByUrl('/login');
  }
}
