import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, ReactiveFormsModule, Validators} from '@angular/forms';
import {Router} from '@angular/router';
import {MessageService} from 'primeng/api';
import {AuthService} from '../../services/auth.service';
import {InputText} from 'primeng/inputtext';
import {Message} from 'primeng/message';
import {Button} from 'primeng/button';
import {Toast} from 'primeng/toast';

interface Response {
  access_token: string;
}
@Component({
  selector: 'bs-login',
  imports: [
    ReactiveFormsModule,
    InputText,
    Message,
    Button,
    Toast
  ],
  providers: [MessageService],
  templateUrl: './login.component.html',
})
export class LoginComponent implements OnInit{
  loginForm: FormGroup;

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router,
    private messageService: MessageService,
  ) {
    this.loginForm = this.fb.group({
      email: '',
      password: ''
    });
  }

  ngOnInit(): void {
    this.loginForm = this.fb.group({
      email: this.fb.control(null, {
        validators: [Validators.required, Validators.email]
      }),
      password: this.fb.control(null, {
        validators: [Validators.required]
      })
    });
  }

  login() {
    const {email, password} = this.loginForm.value; // Werte aus Formular auslesen

    this.authService.login(email, password).subscribe(
      (res:any) => {
        this.authService.setSessionStorage((res as Response).access_token); // Token is sessionStorage speichern
        this.router.navigate(['/']);
      }, () => {
        this.messageService.add({
          severity: 'error',
          summary: 'Login failed',
          detail: 'Invalid email or password'
        });
      }
    )
  }
}
