import { EmailsModule } from './emails.module';

describe('EmailsModule', () => {
  let emailsModule: EmailsModule;

  beforeEach(() => {
    emailsModule = new EmailsModule();
  });

  it('should create an instance', () => {
    expect(emailsModule).toBeTruthy();
  });
});
