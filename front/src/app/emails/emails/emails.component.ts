import {Component, OnInit} from '@angular/core';
import {EmailService} from '../email.service';
import {timer} from 'rxjs';
import {switchMap, tap} from 'rxjs/operators';

@Component({
  selector: 'app-emails',
  templateUrl: './emails.component.html',
  styleUrls: ['./emails.component.scss']
})
export class EmailsComponent implements OnInit {

  constructor(private emailService: EmailService) {
  }

  emails: string[] = [];
  refreshing = false;

  ngOnInit() {
    timer(0, 1000)
      .pipe(
        tap(() => this.refreshing = true),
        switchMap(() => {
          return this.emailService.getEmails();
        }),
        tap(() => this.refreshing = false),
      )
      .subscribe(emails => {
        this.emails = emails;
      });
  }

}
