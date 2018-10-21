import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { EmailsRoutingModule } from './emails-routing.module';
import { EmailsComponent } from './emails/emails.component';
import {EmailService} from './email.service';

@NgModule({
  imports: [
    CommonModule,
    EmailsRoutingModule
  ],
  declarations: [EmailsComponent],
  providers: [EmailService]
})
export class EmailsModule { }
